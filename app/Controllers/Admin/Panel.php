<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\JmfService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Panel extends BaseController
{
    public function index()
    {
        $event = $this->activeEvent;
        $eventId = $event['id'] ?? 0;

        $stats = [
            'perusahaan' => (int) $this->db->table('event_perusahaan ep')
                ->join('perusahaan p', 'p.id = ep.perusahaan_id')
                ->join('users u', 'u.id = p.user_id')
                ->where('ep.event_id', $eventId)
                ->where('u.is_active', 1)
                ->countAllResults(),
            'pencaker'   => (int) $this->db->table('pencaker pck')
                ->join('users u', 'u.id = pck.user_id')
                ->where('u.is_active', 1)
                ->countAllResults(),
            'peserta'    => (int) $this->db->table('event_peserta ep')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('ep.event_id', $eventId)
                ->where('u.is_active', 1)
                ->countAllResults(),
            'hadir'      => (int) $this->db->table('event_peserta ep')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('ep.event_id', $eventId)
                ->where('ep.checkin_at IS NOT NULL')
                ->where('u.is_active', 1)
                ->countAllResults(),
            'lamaran'    => (int) $this->db->query("
                SELECT COUNT(l.id) AS jml
                FROM lamaran l
                JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
                JOIN perusahaan p ON p.id = ep.perusahaan_id
                JOIN users u_comp ON u_comp.id = p.user_id
                JOIN event_peserta eps ON eps.id = l.event_peserta_id
                JOIN pencaker pck ON pck.id = eps.pencaker_id
                JOIN users u_pck ON u_pck.id = pck.user_id
                WHERE ep.event_id = ? AND u_comp.is_active = 1 AND u_pck.is_active = 1
            ", [$eventId])->getRow('jml'),
            'diterima'   => (int) $this->db->query("
                SELECT COUNT(l.id) AS jml
                FROM lamaran l
                JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
                JOIN perusahaan p ON p.id = ep.perusahaan_id
                JOIN users u_comp ON u_comp.id = p.user_id
                JOIN event_peserta eps ON eps.id = l.event_peserta_id
                JOIN pencaker pck ON pck.id = eps.pencaker_id
                JOIN users u_pck ON u_pck.id = pck.user_id
                WHERE ep.event_id = ? AND l.status = 'Diterima' AND u_comp.is_active = 1 AND u_pck.is_active = 1
            ", [$eventId])->getRow('jml'),
            'email_antre'=> (int) $this->db->table('email_queue')->where('status', 'antre')->countAllResults(),
        ];

        return $this->renderView('admin/dashboard', [
            'title' => 'Dashboard Administrator BLK Pasuruan',
            'stats' => $stats,
        ]);
    }

    public function events()
    {
        $list = $this->db->table('events')->orderBy('tahun', 'DESC')->orderBy('id', 'DESC')->get()->getResultArray();
        return $this->renderView('admin/events', [
            'title'  => 'Kelola Event Job Market Fair Tahunan',
            'events' => $list,
        ]);
    }

    public function saveEvent()
    {
        $id = (int) $this->request->getPost('id');
        $status = (string) $this->request->getPost('status');

        if ($status === 'aktif') {
            $this->db->table('events')->where('status', 'aktif')->update(['status' => 'selesai']);
        }

        $data = [
            'nama'               => trim((string) $this->request->getPost('nama')),
            'tahun'              => (int) $this->request->getPost('tahun'),
            'tanggal'            => (string) $this->request->getPost('tanggal'),
            'jam_mulai'          => (string) $this->request->getPost('jam_mulai'),
            'jam_selesai'        => (string) $this->request->getPost('jam_selesai'),
            'lokasi'             => trim((string) $this->request->getPost('lokasi')),
            'deskripsi'          => trim((string) $this->request->getPost('deskripsi')),
            'pendaftaran_dibuka' => $this->request->getPost('pendaftaran_dibuka') ? 1 : 0,
            'lamaran_dibuka'     => $this->request->getPost('lamaran_dibuka') ? 1 : 0,
            'batas_seleksi'      => (string) $this->request->getPost('batas_seleksi') ?: null,
            'sesi_aktif'         => $this->request->getPost('sesi_aktif') ? 1 : 0,
            'aturan_luar_sesi'   => (string) $this->request->getPost('aturan_luar_sesi') === 'tolak' ? 'tolak' : 'peringatan',
            'status'             => $status,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if ($id > 0) {
            $this->db->table('events')->where('id', $id)->update($data);
            return redirect()->to('/admin/events')->with('success', 'Data event berhasil diperbarui.');
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->table('events')->insert($data);
        return redirect()->to('/admin/events')->with('success', 'Event tahunan baru berhasil dibuat.');
    }

    public function sesi()
    {
        $event = $this->activeEvent;
        $sesiList = [];
        $pesertaList = [];
        $tanpaSesiCount = 0;

        if ($event) {
            $sesiList = $this->db->query("
                SELECT s.*,
                       COALESCE(SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END), 0) AS terisi,
                       COALESCE(SUM(CASE WHEN u.is_active = 1 AND ep.checkin_at IS NOT NULL THEN 1 ELSE 0 END), 0) AS hadir
                FROM event_sesi s
                LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
                LEFT JOIN pencaker pck ON pck.id = ep.pencaker_id
                LEFT JOIN users u ON u.id = pck.user_id
                WHERE s.event_id = ?
                GROUP BY s.id
                ORDER BY s.urutan ASC, s.jam_mulai ASC
            ", [$event['id']])->getResultArray();

            $pesertaList = $this->db->query("
                SELECT ep.*, pck.nik, pck.nama, pck.hp, s.nama AS nama_sesi
                FROM event_peserta ep
                JOIN pencaker pck ON pck.id = ep.pencaker_id
                JOIN users u ON u.id = pck.user_id
                LEFT JOIN event_sesi s ON s.id = ep.sesi_id
                WHERE ep.event_id = ? AND u.is_active = 1
                ORDER BY ep.id DESC
            ", [$event['id']])->getResultArray();

            $tanpaSesiCount = (int) $this->db->table('event_peserta ep')
                ->join('pencaker pck', 'pck.id = ep.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('ep.event_id', $event['id'])
                ->where('ep.sesi_id IS NULL')
                ->where('u.is_active', 1)
                ->countAllResults();
        }

        return $this->renderView('admin/sesi', [
            'title'          => 'Pengaturan Jadwal & Kuota Sesi Kedatangan Pelamar',
            'sesiList'       => $sesiList,
            'pesertaList'    => $pesertaList,
            'tanpaSesiCount' => $tanpaSesiCount,
        ]);
    }

    public function savePengaturanSesi()
    {
        $event = $this->activeEvent;
        if ($event) {
            $this->db->table('events')->where('id', $event['id'])->update([
                'sesi_aktif'       => $this->request->getPost('sesi_aktif') ? 1 : 0,
                'aturan_luar_sesi' => $this->request->getPost('aturan_luar_sesi') === 'tolak' ? 'tolak' : 'peringatan',
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->to('/admin/sesi')->with('success', 'Pengaturan sesi kedatangan berhasil disimpan.');
    }

    public function saveSesi()
    {
        $event = $this->activeEvent;
        if (! $event) {
            return redirect()->back();
        }
        $id = (int) $this->request->getPost('id');
        $data = [
            'event_id'    => $event['id'],
            'nama'        => trim((string) $this->request->getPost('nama')),
            'tanggal'     => (string) $this->request->getPost('tanggal'),
            'jam_mulai'   => (string) $this->request->getPost('jam_mulai'),
            'jam_selesai' => (string) $this->request->getPost('jam_selesai'),
            'kuota'       => max(1, (int) $this->request->getPost('kuota')),
            'urutan'      => max(1, (int) $this->request->getPost('urutan')),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($id > 0) {
            $this->db->table('event_sesi')->where('id', $id)->update($data);
            return redirect()->to('/admin/sesi')->with('success', 'Sesi kedatangan diperbarui.');
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->table('event_sesi')->insert($data);
        return redirect()->to('/admin/sesi')->with('success', 'Sesi kedatangan baru ditambahkan.');
    }

    public function deleteSesi(int $id)
    {
        $this->db->table('event_sesi')->where('id', $id)->delete();
        return redirect()->to('/admin/sesi')->with('success', 'Sesi kedatangan dihapus.');
    }

    public function pindahPesertaSesi()
    {
        $pesertaId = (int) $this->request->getPost('event_peserta_id');
        $sesiId    = (int) $this->request->getPost('sesi_id') ?: null;
        $this->db->table('event_sesi');
        $this->db->table('event_peserta')->where('id', $pesertaId)->update([
            'sesi_id'    => $sesiId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/sesi')->with('success', 'Sesi peserta berhasil dipindahkan.');
    }

    public function bagiOtomatisSesi()
    {
        $event = $this->activeEvent;
        if (! $event) {
            return redirect()->back();
        }

        $sesiRows = $this->db->query("
            SELECT s.id, s.kuota,
                   COALESCE(SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END), 0) AS terisi
            FROM event_sesi s
            LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
            LEFT JOIN pencaker pck ON pck.id = ep.pencaker_id
            LEFT JOIN users u ON u.id = pck.user_id
            WHERE s.event_id = ?
            GROUP BY s.id
            ORDER BY s.urutan ASC
        ", [$event['id']])->getResultArray();

        $tanpaSesi = $this->db->table('event_peserta ep')
            ->select('ep.*')
            ->join('pencaker pck', 'pck.id = ep.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->where('ep.event_id', $event['id'])
            ->where('ep.sesi_id IS NULL')
            ->where('u.is_active', 1)
            ->orderBy('ep.id', 'ASC')
            ->get()
            ->getResultArray();

        $assigned = 0;
        foreach ($tanpaSesi as $ps) {
            foreach ($sesiRows as &$sr) {
                if ($sr['terisi'] < $sr['kuota']) {
                    $this->db->table('event_peserta')->where('id', $ps['id'])->update(['sesi_id' => $sr['id']]);
                    $sr['terisi']++;
                    $assigned++;
                    break;
                }
            }
        }

        return redirect()->to('/admin/sesi')->with('success', "{$assigned} peserta tanpa sesi berhasil dibagikan otomatis ke sesi yang tersedia.");
    }

    public function perusahaan()
    {
        $event = $this->activeEvent;
        $eventId = $event['id'] ?? 0;

        $list = $this->db->query("
            SELECT p.*, u.email AS email_login, u.is_active,
                   ep.id AS ep_id, ep.no_booth, ep.qr_token,
                   (SELECT COUNT(l.id) FROM lowongan l WHERE l.event_perusahaan_id = ep.id) AS jml_lowongan
            FROM perusahaan p
            JOIN users u ON u.id = p.user_id
            LEFT JOIN event_perusahaan ep ON ep.perusahaan_id = p.id AND ep.event_id = ?
            ORDER BY ep.no_booth ASC, p.id DESC
        ", [$eventId])->getResultArray();

        return $this->renderView('admin/perusahaan', [
            'title'      => 'Kelola Perusahaan, Akun Login & Booth Event',
            'perusahaan' => $list,
        ]);
    }

    public function savePerusahaan()
    {
        $id = (int) $this->request->getPost('id');
        $nama = trim((string) $this->request->getPost('nama'));
        $email = trim((string) $this->request->getPost('email'));
        $password = trim((string) $this->request->getPost('password'));
        $noBooth = trim((string) $this->request->getPost('no_booth'));
        $now = date('Y-m-d H:i:s');

        if ($id > 0) {
            $p = $this->db->table('perusahaan')->where('id', $id)->get()->getRowArray();
            if ($p) {
                $this->db->table('perusahaan')->where('id', $id)->update([
                    'nama'         => $nama,
                    'bidang_usaha' => trim((string) $this->request->getPost('bidang_usaha')),
                    'alamat'       => trim((string) $this->request->getPost('alamat')),
                    'kota'         => trim((string) $this->request->getPost('kota')),
                    'telp'         => trim((string) $this->request->getPost('telp')),
                    'email'        => $email,
                    'nama_hrd'     => trim((string) $this->request->getPost('nama_hrd')),
                    'hp_hrd'       => trim((string) $this->request->getPost('hp_hrd')),
                    'updated_at'   => $now,
                ]);
                $uUpdate = ['nama' => $nama, 'email' => $email, 'updated_at' => $now];
                if ($password !== '') {
                    $uUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
                $this->db->table('users')->where('id', $p['user_id'])->update($uUpdate);

                if ($this->activeEvent && $noBooth !== '') {
                    $this->assignCompanyBooth($this->activeEvent['id'], $id, $noBooth);
                }
            }
            return redirect()->to('/admin/perusahaan')->with('success', 'Data perusahaan diperbarui.');
        }

        // New company + login account created by Admin
        $exists = $this->db->table('users')->where('email', $email)->countAllResults();
        if ($exists > 0) {
            return redirect()->back()->with('error', 'Email tersebut sudah digunakan oleh akun lain.');
        }

        $passToUse = $password !== '' ? $password : 'Perusahaan123!';
        $this->db->transStart();

        $this->db->table('users')->insert([
            'nama'       => $nama,
            'email'      => $email,
            'password'   => password_hash($passToUse, PASSWORD_DEFAULT),
            'role'       => 'perusahaan',
            'is_active'  => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $userId = $this->db->insertID();

        $this->db->table('perusahaan')->insert([
            'user_id'      => $userId,
            'nama'         => $nama,
            'bidang_usaha' => trim((string) $this->request->getPost('bidang_usaha')),
            'alamat'       => trim((string) $this->request->getPost('alamat')),
            'kota'         => trim((string) $this->request->getPost('kota')),
            'telp'         => trim((string) $this->request->getPost('telp')),
            'email'        => $email,
            'nama_hrd'     => trim((string) $this->request->getPost('nama_hrd')),
            'hp_hrd'       => trim((string) $this->request->getPost('hp_hrd')),
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $perusahaanId = $this->db->insertID();

        if ($this->activeEvent && $noBooth !== '') {
            $this->assignCompanyBooth($this->activeEvent['id'], $perusahaanId, $noBooth);
        }

        $this->db->transComplete();

        return redirect()->to('/admin/perusahaan')->with('success', "Perusahaan & akun login ({$email} / {$passToUse}) berhasil ditambahkan.");
    }

    private function assignCompanyBooth(int $eventId, int $perusahaanId, string $noBooth): void
    {
        $ep = $this->db->table('event_perusahaan')
            ->where('event_id', $eventId)
            ->where('perusahaan_id', $perusahaanId)
            ->get()
            ->getRowArray();

        $now = date('Y-m-d H:i:s');
        if ($ep) {
            $this->db->table('event_perusahaan')->where('id', $ep['id'])->update([
                'no_booth'   => $noBooth,
                'updated_at' => $now,
            ]);
        } else {
            $token = 'BOOTH-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
            $this->db->table('event_perusahaan')->insert([
                'event_id'      => $eventId,
                'perusahaan_id' => $perusahaanId,
                'no_booth'      => $noBooth,
                'qr_token'      => $token,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }

    public function saveBooth()
    {
        $perusahaanId = (int) $this->request->getPost('perusahaan_id');
        $noBooth      = trim((string) $this->request->getPost('no_booth'));
        if ($this->activeEvent && $perusahaanId > 0 && $noBooth !== '') {
            $this->assignCompanyBooth($this->activeEvent['id'], $perusahaanId, $noBooth);
        }
        return redirect()->to('/admin/perusahaan')->with('success', 'Nomor booth perusahaan diperbarui.');
    }

    public function resetPasswordPerusahaan(int $userId)
    {
        $newPass = 'Perusahaan123!';
        $this->db->table('users')->where('id', $userId)->update([
            'password'   => password_hash($newPass, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/perusahaan')->with('success', 'Password akun perusahaan berhasil di-reset menjadi: ' . $newPass);
    }

    public function posterQr(int $epId)
    {
        $ep = $this->db->table('event_perusahaan')->where('id', $epId)->get()->getRowArray();
        if (! $ep) {
            return redirect()->back()->with('error', 'Booth tidak ditemukan.');
        }
        $p = $this->db->table('perusahaan')->where('id', $ep['perusahaan_id'])->get()->getRowArray();
        $lowonganList = $this->db->table('lowongan')->where('event_perusahaan_id', $ep['id'])->where('is_active', 1)->get()->getResultArray();
        $lamarUrl = base_url('lamar/' . $ep['qr_token']);
        $qrDataUri = JmfService::generateQrDataUri($lamarUrl);

        $html = view('pdf/poster_booth', [
            'pengaturan'   => $this->pengaturan,
            'event'        => $this->activeEvent,
            'perusahaan'   => $p,
            'ep'           => $ep,
            'lowonganList' => $lowonganList,
            'qrDataUri'    => $qrDataUri,
            'lamarUrl'     => $lamarUrl,
        ]);

        return JmfService::renderPdf($html, 'Poster-QR-Booth-' . $ep['no_booth'] . '.pdf', 'A4', 'portrait');
    }

    public function lowongan()
    {
        $list = $this->db->query("
            SELECT l.*, p.nama AS nama_perusahaan, ep.no_booth,
                   (SELECT COUNT(lam.id) FROM lamaran lam WHERE lam.lowongan_id = l.id) AS jml_pelamar
            FROM lowongan l
            JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            ORDER BY ep.no_booth ASC, l.id DESC
        ")->getResultArray();

        return $this->renderView('admin/lowongan', [
            'title'        => 'Monitoring Semua Lowongan Perusahaan',
            'lowonganList' => $list,
        ]);
    }

    public function toggleLowongan(int $id)
    {
        $row = $this->db->table('lowongan')->where('id', $id)->get()->getRowArray();
        if ($row) {
            $this->db->table('lowongan')->where('id', $id)->update(['is_active' => $row['is_active'] ? 0 : 1]);
        }
        return redirect()->to('/admin/lowongan')->with('success', 'Status aktif lowongan diperbarui.');
    }

    public function pencaker()
    {
        $list = $this->db->query("
            SELECT pck.*, u.email, u.is_active, k.nama AS nama_kecamatan, d.nama AS nama_desa,
                   ep.no_registrasi, ep.checkin_at, s.nama AS nama_sesi
            FROM pencaker pck
            JOIN users u ON u.id = pck.user_id
            LEFT JOIN desa d ON d.id = pck.desa_id
            LEFT JOIN kecamatan k ON k.id = d.kecamatan_id
            LEFT JOIN event_peserta ep ON ep.pencaker_id = pck.id AND ep.event_id = ?
            LEFT JOIN event_sesi s ON s.id = ep.sesi_id
            ORDER BY pck.id DESC
        ", [$this->activeEvent['id'] ?? 0])->getResultArray();

        return $this->renderView('admin/pencaker', [
            'title'    => 'Data Pencari Kerja & Peserta Event',
            'pencaker' => $list,
        ]);
    }

    public function exportPencakerExcel()
    {
        $event = $this->activeEvent;
        $rows = $this->db->query("
            SELECT pck.*, u.email, u.is_active, k.nama AS nama_kecamatan, d.nama AS nama_desa,
                   ep.no_registrasi, ep.checkin_at, s.nama AS nama_sesi
            FROM pencaker pck
            JOIN users u ON u.id = pck.user_id
            LEFT JOIN desa d ON d.id = pck.desa_id
            LEFT JOIN kecamatan k ON k.id = d.kecamatan_id
            LEFT JOIN event_peserta ep ON ep.pencaker_id = pck.id AND ep.event_id = ?
            LEFT JOIN event_sesi s ON s.id = ep.sesi_id
            WHERE u.is_active = 1
            ORDER BY pck.id ASC
        ", [$event['id'] ?? 0])->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pencari Kerja');

        $sheet->setCellValue('A1', 'DATA PENCARI KERJA TERDAFTAR - ' . strtoupper($event['nama'] ?? 'JOB MARKET FAIR UPT BLK PASURUAN'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);

        $headers = [
            'No',
            'No. Registrasi Event',
            'Sesi Kedatangan',
            'Status Kehadiran',
            'Waktu Check-in',
            'NIK',
            'Nama Lengkap',
            'L/P',
            'Tempat Lahir',
            'Tgl Lahir',
            'Pendidikan',
            'Jurusan',
            'Domisili',
            'Alamat Lengkap',
            'No. HP/WA',
            'Email',
            'Disabilitas',
            'Link Berkas PDF',
        ];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $col++;
        }

        $rowNum = 4;
        foreach ($rows as $idx => $r) {
            $dom = $r['domisili'] === 'kab_pasuruan'
                ? trim(($r['nama_desa'] ?? '') . ', Kec. ' . ($r['nama_kecamatan'] ?? '') . ', Kab. Pasuruan', ', ')
                : ($r['kota_luar'] ?? '-');

            $statusHadir = ! empty($r['no_registrasi'])
                ? (! empty($r['checkin_at']) ? 'Hadir Check-in' : 'Belum Check-in')
                : 'Belum Daftar Event';

            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $r['no_registrasi'] ?: '-');
            $sheet->setCellValue('C' . $rowNum, ! empty($r['no_registrasi']) ? ($r['nama_sesi'] ?: 'Bebas') : '-');
            $sheet->setCellValue('D' . $rowNum, $statusHadir);
            $sheet->setCellValue('E' . $rowNum, $r['checkin_at'] ?: '-');
            $sheet->setCellValueExplicit('F' . $rowNum, (string) $r['nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $rowNum, $r['nama']);
            $sheet->setCellValue('H' . $rowNum, $r['jk']);
            $sheet->setCellValue('I' . $rowNum, $r['tempat_lahir'] ?: '-');
            $sheet->setCellValue('J' . $rowNum, $r['tgl_lahir'] ?: '-');
            $sheet->setCellValue('K' . $rowNum, $r['pendidikan']);
            $sheet->setCellValue('L' . $rowNum, $r['jurusan'] ?: '-');
            $sheet->setCellValue('M' . $rowNum, $dom);
            $sheet->setCellValue('N' . $rowNum, $r['alamat'] ?: '-');
            $sheet->setCellValueExplicit('O' . $rowNum, (string) $r['hp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('P' . $rowNum, $r['email']);
            $sheet->setCellValue('Q' . $rowNum, $r['disabilitas'] ? ('Ya (' . ($r['jenis_disabilitas'] ?: '-') . ')') : 'Tidak');
            $sheet->setCellValue('R' . $rowNum, $r['link_berkas'] ?: '-');
            $rowNum++;
        }

        foreach (range('A', 'R') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return $this->response
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="Data-Pencari-Kerja-JMF-BLK-Pasuruan.xlsx"')
            ->setBody($excelOutput);
    }

    public function resetPasswordPencaker(int $userId)
    {
        $newPass = '123456';
        $this->db->table('users')->where('id', $userId)->where('role', 'pencaker')->update([
            'password'   => password_hash($newPass, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/pencaker')->with('success', 'Password akun pencari kerja berhasil di-reset menjadi: ' . $newPass);
    }

    public function toggleUser(int $userId)
    {
        $u = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if ($u && $u['role'] !== 'admin') {
            $newActive = $u['is_active'] ? 0 : 1;
            $this->db->table('users')->where('id', $userId)->update(['is_active' => $newActive]);

            if ($u['role'] === 'perusahaan') {
                $p = $this->db->table('perusahaan')->where('user_id', $userId)->get()->getRowArray();
                if ($p) {
                    $eps = $this->db->table('event_perusahaan')->where('perusahaan_id', $p['id'])->get()->getResultArray();
                    foreach ($eps as $ep) {
                        $this->db->table('lowongan')->where('event_perusahaan_id', $ep['id'])->update(['is_active' => $newActive]);
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Status aktif akun diperbarui.');
    }

    public function pengguna()
    {
        $users = $this->db->table('users')
            ->whereIn('role', ['admin', 'petugas', 'pimpinan'])
            ->orderBy('role', 'ASC')
            ->get()
            ->getResultArray();

        return $this->renderView('admin/pengguna', [
            'title' => 'Kelola Akun Internal (Admin, Petugas Registrasi & Pimpinan)',
            'users' => $users,
        ]);
    }

    public function savePengguna()
    {
        $id    = (int) $this->request->getPost('id');
        $nama  = trim((string) $this->request->getPost('nama'));
        $email = trim((string) $this->request->getPost('email'));
        $role  = (string) $this->request->getPost('role');
        $pass  = trim((string) $this->request->getPost('password'));
        $now   = date('Y-m-d H:i:s');

        if (! in_array($role, ['admin', 'petugas', 'pimpinan'], true)) {
            $role = 'petugas';
        }

        if ($id > 0) {
            $upd = ['nama' => $nama, 'email' => $email, 'role' => $role, 'updated_at' => $now];
            if ($pass !== '') {
                $upd['password'] = password_hash($pass, PASSWORD_DEFAULT);
            }
            $this->db->table('users')->where('id', $id)->update($upd);
            return redirect()->to('/admin/pengguna')->with('success', 'Akun pengguna internal diperbarui.');
        }

        $this->db->table('users')->insert([
            'nama'       => $nama,
            'email'      => $email,
            'password'   => password_hash($pass ?: 'Petugas123!', PASSWORD_DEFAULT),
            'role'       => $role,
            'is_active'  => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()->to('/admin/pengguna')->with('success', 'Akun pengguna internal baru ditambahkan.');
    }

    public function survei()
    {
        $event = $this->activeEvent;
        $pertanyaan = [];
        $saranList = [];

        if ($event) {
            $pertanyaan = $this->db->table('survei_pertanyaan')
                ->where('event_id', $event['id'])
                ->orderBy('target', 'ASC')
                ->orderBy('urutan', 'ASC')
                ->get()
                ->getResultArray();

            $saranList = $this->db->query("
                SELECT sj.*, sp.target, sp.pertanyaan, u.nama
                FROM survei_jawaban sj
                JOIN survei_pertanyaan sp ON sp.id = sj.pertanyaan_id
                JOIN users u ON u.id = sj.user_id
                WHERE sp.event_id = ? AND sp.tipe = 'teks' AND sj.jawaban_teks IS NOT NULL AND sj.jawaban_teks != ''
                ORDER BY sj.id DESC
            ", [$event['id']])->getResultArray();
        }

        return $this->renderView('admin/survei', [
            'title'      => 'Kelola Pertanyaan Survei Kepuasan',
            'pertanyaan' => $pertanyaan,
            'saranList'  => $saranList,
        ]);
    }

    public function saveSurveiPertanyaan()
    {
        $event = $this->activeEvent;
        if (! $event) {
            return redirect()->back();
        }
        $this->db->table('survei_pertanyaan')->insert([
            'event_id'   => $event['id'],
            'target'     => $this->request->getPost('target') === 'perusahaan' ? 'perusahaan' : 'pencaker',
            'pertanyaan' => trim((string) $this->request->getPost('pertanyaan')),
            'tipe'       => $this->request->getPost('tipe') === 'teks' ? 'teks' : 'skala',
            'urutan'     => (int) $this->request->getPost('urutan') ?: 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/survei')->with('success', 'Pertanyaan survei ditambahkan.');
    }

    public function deleteSurveiPertanyaan(int $id)
    {
        $this->db->table('survei_pertanyaan')->where('id', $id)->delete();
        return redirect()->to('/admin/survei')->with('success', 'Pertanyaan survei dihapus.');
    }

    public function pengaturan()
    {
        $antrean = $this->db->table('email_queue')->orderBy('id', 'DESC')->limit(25)->get()->getResultArray();
        return $this->renderView('admin/pengaturan', [
            'title'   => 'Pengaturan Instansi, Kop Surat & Email SMTP',
            'antrean' => $antrean,
        ]);
    }

    public function savePengaturan()
    {
        $keys = [
            'nama_instansi', 'sub_instansi', 'alamat_instansi', 'telp_instansi',
            'email_instansi', 'website_instansi', 'kepala_blk', 'nip_kepala_blk',
            'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_crypto', 'smtp_from_name'
        ];
        $now = date('Y-m-d H:i:s');
        foreach ($keys as $k) {
            $val = $this->request->getPost($k);
            if ($val !== null) {
                $this->db->table('pengaturan')->replace([
                    'key'        => $k,
                    'value'      => trim((string) $val),
                    'updated_at' => $now,
                ]);
            }
        }
        return redirect()->to('/admin/pengaturan')->with('success', 'Pengaturan instansi dan SMTP berhasil disimpan.');
    }

    public function prosesEmailQueue()
    {
        $res = JmfService::processEmailQueue(30);
        return redirect()->to('/admin/pengaturan')->with('success', "Antrean email diproses: {$res['total']} (Terkirim: {$res['sent']}, Gagal: {$res['failed']}).");
    }
}
