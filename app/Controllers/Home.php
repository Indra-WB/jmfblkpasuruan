<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $event = $this->activeEvent;
        $sesiList = [];
        $stats = [
            'perusahaan' => 0,
            'lowongan'   => 0,
            'kuota'      => 0,
            'peserta'    => 0,
        ];
        $featuredLowongan = [];

        if ($event) {
            if (! empty($event['sesi_aktif'])) {
                $sesiList = $this->db->query("
                    SELECT s.*, COALESCE(SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END), 0) AS terisi
                    FROM event_sesi s
                    LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
                    LEFT JOIN pencaker pck ON pck.id = ep.pencaker_id
                    LEFT JOIN users u ON u.id = pck.user_id
                    WHERE s.event_id = ?
                    GROUP BY s.id
                    ORDER BY s.urutan ASC, s.jam_mulai ASC
                ", [$event['id']])->getResultArray();
            }

            $stats['perusahaan'] = (int) $this->db->table('event_perusahaan ep')
                ->join('perusahaan p', 'p.id = ep.perusahaan_id')
                ->join('users u', 'u.id = p.user_id')
                ->where('ep.event_id', $event['id'])
                ->where('u.is_active', 1)
                ->countAllResults();

            $stats['peserta'] = (int) $this->db->table('event_peserta ep')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('ep.event_id', $event['id'])
                ->where('u.is_active', 1)
                ->countAllResults();

            $lowSummary = $this->db->query("
                SELECT COUNT(l.id) AS jml_lowongan, COALESCE(SUM(l.kuota), 0) AS total_kuota
                FROM lowongan l
                JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
                JOIN perusahaan p ON p.id = ep.perusahaan_id
                JOIN users u ON u.id = p.user_id
                WHERE ep.event_id = ? AND l.is_active = 1 AND u.is_active = 1
            ", [$event['id']])->getRowArray();

            $stats['lowongan'] = (int) ($lowSummary['jml_lowongan'] ?? 0);
            $stats['kuota']    = (int) ($lowSummary['total_kuota'] ?? 0);

            $featuredLowongan = $this->db->query("
                SELECT l.*, p.nama AS nama_perusahaan, p.logo, p.bidang_usaha, ep.no_booth
                FROM lowongan l
                JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
                JOIN perusahaan p ON p.id = ep.perusahaan_id
                JOIN users u ON u.id = p.user_id
                WHERE ep.event_id = ? AND l.is_active = 1 AND u.is_active = 1
                ORDER BY l.id DESC
                LIMIT 6
            ", [$event['id']])->getResultArray();
        }

        return $this->renderView('publik/home', [
            'title'            => 'Beranda',
            'sesiList'         => $sesiList,
            'stats'            => $stats,
            'featuredLowongan' => $featuredLowongan,
        ]);
    }

    public function lowongan()
    {
        $event = $this->activeEvent;
        $q           = trim((string) $this->request->getGet('q'));
        $pendidikan  = trim((string) $this->request->getGet('pendidikan'));
        $disabilitas = $this->request->getGet('disabilitas');

        $builder = $this->db->table('lowongan l')
            ->select('l.*, p.nama AS nama_perusahaan, p.logo, p.bidang_usaha, ep.no_booth')
            ->join('event_perusahaan ep', 'ep.id = l.event_perusahaan_id')
            ->join('perusahaan p', 'p.id = ep.perusahaan_id')
            ->where('l.is_active', 1);

        if ($event) {
            $builder->where('ep.event_id', $event['id']);
        }
        if ($q !== '') {
            $builder->groupStart()
                ->like('l.jabatan', $q)
                ->orLike('p.nama', $q)
                ->orLike('l.deskripsi', $q)
                ->groupEnd();
        }
        if ($pendidikan !== '') {
            $builder->where('l.pendidikan_min', $pendidikan);
        }
        if ($disabilitas === '1') {
            $builder->where('l.ramah_disabilitas', 1);
        }

        $lowonganList = $builder->orderBy('ep.no_booth', 'ASC')->get()->getResultArray();

        return $this->renderView('publik/lowongan', [
            'title'        => 'Daftar Lowongan Kerja',
            'lowonganList' => $lowonganList,
            'q'            => $q,
            'pendidikan'   => $pendidikan,
            'disabilitas'  => $disabilitas,
        ]);
    }

    public function detailLowongan(int $id)
    {
        $low = $this->db->table('lowongan l')
            ->select('l.*, p.nama AS nama_perusahaan, p.logo, p.bidang_usaha, p.alamat, p.website, p.deskripsi AS deskripsi_perusahaan, ep.no_booth, ep.event_id')
            ->join('event_perusahaan ep', 'ep.id = l.event_perusahaan_id')
            ->join('perusahaan p', 'p.id = ep.perusahaan_id')
            ->where('l.id', $id)
            ->get()
            ->getRowArray();

        if (! $low) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lowongan tidak ditemukan.');
        }

        return $this->renderView('publik/detail_lowongan', [
            'title' => $low['jabatan'] . ' - ' . $low['nama_perusahaan'],
            'low'   => $low,
        ]);
    }

    public function perusahaan()
    {
        $event = $this->activeEvent;
        $list = [];
        if ($event) {
            $list = $this->db->query("
                SELECT p.*, ep.no_booth, COUNT(l.id) AS jml_lowongan, COALESCE(SUM(l.kuota), 0) AS total_kuota
                FROM event_perusahaan ep
                JOIN perusahaan p ON p.id = ep.perusahaan_id
                JOIN users u ON u.id = p.user_id
                LEFT JOIN lowongan l ON l.event_perusahaan_id = ep.id AND l.is_active = 1
                WHERE ep.event_id = ? AND u.is_active = 1
                GROUP BY ep.id
                ORDER BY ep.no_booth ASC
            ", [$event['id']])->getResultArray();
        }

        return $this->renderView('publik/perusahaan', [
            'title'      => 'Perusahaan Peserta Job Market Fair',
            'perusahaan' => $list,
        ]);
    }

    public function desaByKecamatan(int $kecamatanId)
    {
        $desa = $this->db->table('desa')
            ->where('kecamatan_id', $kecamatanId)
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($desa);
    }

    public function foto(string $filename)
    {
        $safe = basename($filename);
        $path = WRITEPATH . 'uploads/foto/' . $safe;
        if (! is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $mime = mime_content_type($path) ?: 'image/jpeg';
        return $this->response->setContentType($mime)->setBody(file_get_contents($path));
    }

    public function logo(string $filename)
    {
        $safe = basename($filename);
        $path = WRITEPATH . 'uploads/logo/' . $safe;
        if (! is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $mime = mime_content_type($path) ?: 'image/png';
        return $this->response->setContentType($mime)->setBody(file_get_contents($path));
    }
}
