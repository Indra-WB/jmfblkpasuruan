<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Libraries\JmfService;

class Checkin extends BaseController
{
    private function getDashboardStats(array $event): array
    {
        $stats = [
            'total_terdaftar' => (int) $this->db->table('event_peserta ep')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('ep.event_id', $event['id'])
                ->where('u.is_active', 1)
                ->countAllResults(),
            'total_hadir'     => (int) $this->db->table('event_peserta ep')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('ep.event_id', $event['id'])
                ->where('ep.checkin_at IS NOT NULL')
                ->where('u.is_active', 1)
                ->countAllResults(),
        ];
        $stats['belum_checkin'] = max(0, $stats['total_terdaftar'] - $stats['total_hadir']);

        $sesiStats = [];
        if (! empty($event['sesi_aktif'])) {
            $sesiStats = $this->db->query("
                SELECT s.*,
                       COALESCE(SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END), 0) AS terdaftar,
                       COALESCE(SUM(CASE WHEN u.is_active = 1 AND ep.checkin_at IS NOT NULL THEN 1 ELSE 0 END), 0) AS hadir
                FROM event_sesi s
                LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
                LEFT JOIN pencaker pck ON pck.id = ep.pencaker_id
                LEFT JOIN users u ON u.id = pck.user_id
                WHERE s.event_id = ?
                GROUP BY s.id
                ORDER BY s.urutan ASC
            ", [$event['id']])->getResultArray();
        }

        $recentCheckin = $this->db->query("
            SELECT ep.no_registrasi, ep.checkin_at, ep.checkin_luar_sesi, pck.nik, pck.nama, s.nama AS nama_sesi
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            JOIN users u ON u.id = pck.user_id
            LEFT JOIN event_sesi s ON s.id = ep.sesi_id
            WHERE ep.event_id = ? AND ep.checkin_at IS NOT NULL AND u.is_active = 1
            ORDER BY ep.checkin_at DESC
            LIMIT 10
        ", [$event['id']])->getResultArray();

        foreach ($recentCheckin as &$rc) {
            $rc['jam_checkin'] = date('H:i:s', strtotime($rc['checkin_at']));
        }

        return [
            'stats'         => $stats,
            'sesiStats'     => $sesiStats,
            'recentCheckin' => $recentCheckin,
        ];
    }

    public function index()
    {
        $event = $this->activeEvent;
        $data = $event ? $this->getDashboardStats($event) : [
            'stats'         => ['total_terdaftar' => 0, 'total_hadir' => 0, 'belum_checkin' => 0],
            'sesiStats'     => [],
            'recentCheckin' => [],
        ];

        return $this->renderView('petugas/scanner', [
            'title'         => 'Meja Registrasi & Scanner Check-in Peserta',
            'stats'         => $data['stats'],
            'sesiStats'     => $data['sesiStats'],
            'recentCheckin' => $data['recentCheckin'],
        ]);
    }

    public function verify()
    {
        $kode = trim((string) $this->request->getPost('kode'));
        $event = $this->activeEvent;
        if (! $event) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Tidak ada event aktif.']);
        }

        $row = $this->db->table('event_peserta ep')
            ->select('ep.*, pck.nik, pck.nama, pck.pendidikan, pck.jurusan, pck.hp, pck.foto, pck.profil_lengkap, pck.link_berkas, s.id AS s_id, s.nama AS nama_sesi, s.tanggal AS tgl_sesi, s.jam_mulai, s.jam_selesai')
            ->join('pencaker pck', 'pck.id = ep.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->join('event_sesi s', 's.id = ep.sesi_id', 'left')
            ->where('ep.event_id', $event['id'])
            ->where('u.is_active', 1)
            ->groupStart()
                ->where('ep.qr_token', $kode)
                ->orWhere('ep.no_registrasi', $kode)
                ->orWhere('pck.nik', $kode)
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (! $row) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Data peserta aktif dengan QR / NIK "' . esc($kode) . '" tidak ditemukan pada event aktif.', 'csrfHash' => csrf_hash()]);
        }

        $sesiObj = $row['s_id'] ? [
            'id'          => $row['s_id'],
            'nama'        => $row['nama_sesi'],
            'tanggal'     => $row['tgl_sesi'],
            'jam_mulai'   => $row['jam_mulai'],
            'jam_selesai' => $row['jam_selesai'],
        ] : null;

        $evalSesi = JmfService::evaluateSesiStatus($event, $sesiObj);

        return $this->response->setJSON([
            'ok'       => true,
            'peserta'  => $row,
            'evalSesi' => $evalSesi,
            'fotoUrl'  => ! empty($row['foto']) ? base_url('media/foto/' . $row['foto']) : null,
            'csrfHash' => csrf_hash(),
        ]);
    }

    public function confirm()
    {
        $pesertaId = (int) $this->request->getPost('event_peserta_id');
        $event = $this->activeEvent;
        $isAjax = $this->request->isAJAX();

        $row = $this->db->table('event_peserta ep')
            ->select('ep.*, pck.nama, s.id AS s_id, s.nama AS nama_sesi, s.tanggal AS tgl_sesi, s.jam_mulai, s.jam_selesai')
            ->join('pencaker pck', 'pck.id = ep.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->join('event_sesi s', 's.id = ep.sesi_id', 'left')
            ->where('ep.id', $pesertaId)
            ->where('u.is_active', 1)
            ->get()
            ->getRowArray();

        if (! $row) {
            $msg = 'Peserta tidak ditemukan.';
            if ($isAjax) {
                return $this->response->setJSON(['ok' => false, 'msg' => $msg, 'csrfHash' => csrf_hash()]);
            }
            return redirect()->to('/petugas')->with('error', $msg);
        }

        if (! empty($row['checkin_at'])) {
            $msg = 'Peserta ' . $row['nama'] . ' sudah tercatat Check-in sebelumnya pada ' . $row['checkin_at'];
            if ($isAjax) {
                return $this->response->setJSON(['ok' => false, 'msg' => $msg, 'csrfHash' => csrf_hash()]);
            }
            return redirect()->to('/petugas')->with('error', $msg);
        }

        $sesiObj = $row['s_id'] ? [
            'id'          => $row['s_id'],
            'nama'        => $row['nama_sesi'],
            'tanggal'     => $row['tgl_sesi'],
            'jam_mulai'   => $row['jam_mulai'],
            'jam_selesai' => $row['jam_selesai'],
        ] : null;

        $evalSesi = JmfService::evaluateSesiStatus($event, $sesiObj);
        if (! $evalSesi['allowed']) {
            $msg = 'Check-in Ditolak: ' . $evalSesi['label'] . ' (Aturan event menolak peserta di luar jam sesi).';
            if ($isAjax) {
                return $this->response->setJSON(['ok' => false, 'msg' => $msg, 'csrfHash' => csrf_hash()]);
            }
            return redirect()->to('/petugas')->with('error', $msg);
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('event_peserta')->where('id', $pesertaId)->update([
            'checkin_at'        => $now,
            'checkin_by'        => session()->get('user_id'),
            'checkin_luar_sesi' => $evalSesi['luar'],
            'updated_at'        => $now,
        ]);

        $msg = 'Check-in BERHASIL untuk peserta: ' . $row['nama'] . ' (' . $row['no_registrasi'] . ').';
        if ($isAjax) {
            $dash = $event ? $this->getDashboardStats($event) : [];
            return $this->response->setJSON([
                'ok'            => true,
                'msg'           => $msg,
                'csrfHash'      => csrf_hash(),
                'stats'         => $dash['stats'] ?? null,
                'sesiStats'     => $dash['sesiStats'] ?? [],
                'recentCheckin' => $dash['recentCheckin'] ?? [],
            ]);
        }

        return redirect()->to('/petugas')->with('success', $msg);
    }

    public function cari()
    {
        $q = trim((string) $this->request->getGet('q'));
        $event = $this->activeEvent;
        $results = [];
        if ($event && strlen($q) >= 3) {
            $results = $this->db->table('event_peserta ep')
                ->select('ep.id, ep.no_registrasi, ep.qr_token, ep.checkin_at, pck.nik, pck.nama, s.nama AS nama_sesi')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->join('event_sesi s', 's.id = ep.sesi_id', 'left')
                ->where('ep.event_id', $event['id'])
                ->where('u.is_active', 1)
                ->groupStart()
                    ->like('pck.nama', $q)
                    ->orLike('pck.nik', $q)
                    ->orLike('ep.no_registrasi', $q)
                ->groupEnd()
                ->limit(15)
                ->get()
                ->getResultArray();
        }
        return $this->response->setJSON($results);
    }
}
