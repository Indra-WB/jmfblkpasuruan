<?php

namespace App\Controllers\Perusahaan;

use App\Controllers\BaseController;
use App\Libraries\JmfService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Panel extends BaseController
{
    private function getPerusahaan(): array
    {
        $userId = (int) session()->get('user_id');
        $p = $this->db->table('perusahaan')->where('user_id', $userId)->get()->getRowArray();
        return $p ?: [];
    }

    private function getEventPerusahaan(): ?array
    {
        $p = $this->getPerusahaan();
        $event = $this->activeEvent;
        if (! $event || empty($p['id'])) {
            return null;
        }
        return $this->db->table('event_perusahaan')
            ->where('event_id', $event['id'])
            ->where('perusahaan_id', $p['id'])
            ->get()
            ->getRowArray();
    }

    private function isSeleksiClosed(): bool
    {
        if (! $this->activeEvent || empty($this->activeEvent['batas_seleksi'])) {
            return false;
        }
        return date('Y-m-d') > $this->activeEvent['batas_seleksi'];
    }

    public function index()
    {
        $p = $this->getPerusahaan();
        $ep = $this->getEventPerusahaan();
        $stats = [
            'lowongan'   => 0,
            'kuota'      => 0,
            'pelamar'    => 0,
            'diterima'   => 0,
            'panggilTes' => 0,
        ];
        $qrSvg = null;
        $lamarUrl = null;

        if ($ep) {
            $lamarUrl = base_url('lamar/' . $ep['qr_token']);
            $qrSvg = JmfService::generateQrDataUri($lamarUrl);

            $lowSummary = $this->db->query("
                SELECT COUNT(id) AS jml, COALESCE(SUM(kuota), 0) AS kuota
                FROM lowongan
                WHERE event_perusahaan_id = ?
            ", [$ep['id']])->getRowArray();

            $stats['lowongan'] = (int) ($lowSummary['jml'] ?? 0);
            $stats['kuota']    = (int) ($lowSummary['kuota'] ?? 0);

            $stats['pelamar']    = (int) $this->db->table('lamaran')->where('event_perusahaan_id', $ep['id'])->countAllResults();
            $stats['diterima']   = (int) $this->db->table('lamaran')->where('event_perusahaan_id', $ep['id'])->where('status', 'Diterima')->countAllResults();
            $stats['panggilTes'] = (int) $this->db->table('lamaran')->where('event_perusahaan_id', $ep['id'])->where('status', 'PanggilTes')->countAllResults();
        }

        return $this->renderView('perusahaan/dashboard', [
            'title'         => 'Dashboard Perusahaan',
            'perusahaan'    => $p,
            'ep'            => $ep,
            'stats'         => $stats,
            'qrSvg'         => $qrSvg,
            'lamarUrl'      => $lamarUrl,
            'seleksiClosed' => $this->isSeleksiClosed(),
        ]);
    }

    public function profil()
    {
        return $this->renderView('perusahaan/profil', [
            'title'      => 'Profil Perusahaan',
            'perusahaan' => $this->getPerusahaan(),
        ]);
    }

    public function saveProfil()
    {
        $p = $this->getPerusahaan();
        $logoFilename = $p['logo'];

        $fileLogo = $this->request->getFile('logo');
        if ($fileLogo && $fileLogo->isValid() && ! $fileLogo->hasMoved()) {
            $rules = [
                'logo' => 'uploaded[logo]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]|max_size[logo,1024]',
            ];
            if (! $this->validate($rules)) {
                return redirect()->back()->with('error', 'Logo harus berformat JPG/PNG dan maksimal 1 MB.');
            }
            $newName = $fileLogo->getRandomName();
            $fileLogo->move(WRITEPATH . 'uploads/logo', $newName);
            $logoFilename = $newName;
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $this->db->table('perusahaan')->where('id', $p['id'])->update([
            'nama'         => $nama,
            'bidang_usaha' => trim((string) $this->request->getPost('bidang_usaha')),
            'alamat'       => trim((string) $this->request->getPost('alamat')),
            'kota'         => trim((string) $this->request->getPost('kota')),
            'telp'         => trim((string) $this->request->getPost('telp')),
            'email'        => trim((string) $this->request->getPost('email')),
            'website'      => trim((string) $this->request->getPost('website')),
            'deskripsi'    => trim((string) $this->request->getPost('deskripsi')),
            'nama_hrd'     => trim((string) $this->request->getPost('nama_hrd')),
            'hp_hrd'       => trim((string) $this->request->getPost('hp_hrd')),
            'logo'         => $logoFilename,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('users')->where('id', $p['user_id'])->update(['nama' => $nama]);
        session()->set('nama', $nama);

        return redirect()->to('/perusahaan/profil')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    public function lowongan()
    {
        $ep = $this->getEventPerusahaan();
        $list = [];
        if ($ep) {
            $list = $this->db->query("
                SELECT l.*, COUNT(lam.id) AS jml_pelamar
                FROM lowongan l
                LEFT JOIN lamaran lam ON lam.lowongan_id = l.id
                WHERE l.event_perusahaan_id = ?
                GROUP BY l.id
                ORDER BY l.id DESC
            ", [$ep['id']])->getResultArray();
        }

        return $this->renderView('perusahaan/lowongan', [
            'title'        => 'Kelola Lowongan Kerja',
            'ep'           => $ep,
            'lowonganList' => $list,
        ]);
    }

    public function saveLowongan()
    {
        $ep = $this->getEventPerusahaan();
        if (! $ep) {
            return redirect()->back()->with('error', 'Perusahaan Anda belum terdaftar di event aktif. Hubungi Admin BLK.');
        }

        $id = (int) $this->request->getPost('id');
        $data = [
            'event_perusahaan_id' => $ep['id'],
            'jabatan'             => trim((string) $this->request->getPost('jabatan')),
            'deskripsi'           => trim((string) $this->request->getPost('deskripsi')),
            'kualifikasi'         => trim((string) $this->request->getPost('kualifikasi')),
            'pendidikan_min'      => (string) $this->request->getPost('pendidikan_min'),
            'jenis_kelamin'       => (string) $this->request->getPost('jenis_kelamin'),
            'usia_max'            => (int) $this->request->getPost('usia_max') ?: null,
            'kuota'               => max(1, (int) $this->request->getPost('kuota')),
            'gaji_min'            => (int) $this->request->getPost('gaji_min') ?: null,
            'gaji_max'            => (int) $this->request->getPost('gaji_max') ?: null,
            'lokasi_kerja'        => trim((string) $this->request->getPost('lokasi_kerja')) ?: 'Pasuruan',
            'ramah_disabilitas'   => $this->request->getPost('ramah_disabilitas') ? 1 : 0,
            'is_active'           => $this->request->getPost('is_active') ? 1 : 0,
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        if ($id > 0) {
            $this->db->table('lowongan')
                ->where('id', $id)
                ->where('event_perusahaan_id', $ep['id'])
                ->update($data);
            return redirect()->to('/perusahaan/lowongan')->with('success', 'Lowongan berhasil diperbarui.');
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->table('lowongan')->insert($data);
        return redirect()->to('/perusahaan/lowongan')->with('success', 'Lowongan baru berhasil ditambahkan.');
    }

    public function deleteLowongan(int $id)
    {
        $ep = $this->getEventPerusahaan();
        if ($ep) {
            $this->db->table('lowongan')
                ->where('id', $id)
                ->where('event_perusahaan_id', $ep['id'])
                ->delete();
        }
        return redirect()->to('/perusahaan/lowongan')->with('success', 'Lowongan berhasil dihapus.');
    }

    public function pelamar()
    {
        $ep = $this->getEventPerusahaan();
        $lowonganFilter   = (int) $this->request->getGet('lowongan_id');
        $pendidikanFilter = trim((string) $this->request->getGet('pendidikan'));
        $jkFilter         = trim((string) $this->request->getGet('jk'));
        $statusFilter     = trim((string) $this->request->getGet('status'));

        $pelamarList = [];
        $lowonganOpts = [];

        if ($ep) {
            $lowonganOpts = $this->db->table('lowongan')->where('event_perusahaan_id', $ep['id'])->get()->getResultArray();

            $builder = $this->db->table('lamaran l')
                ->select('l.*, low.jabatan, pck.nik, pck.nama, pck.jk, pck.tgl_lahir, pck.pendidikan, pck.jurusan, pck.hp, pck.domisili, pck.kota_luar, pck.disabilitas, pck.link_berkas, k.nama AS nama_kecamatan, u.email')
                ->join('lowongan low', 'low.id = l.lowongan_id')
                ->join('event_peserta eps', 'eps.id = l.event_peserta_id')
                ->join('pencaker pck', 'pck.id = eps.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->join('desa d', 'd.id = pck.desa_id', 'left')
                ->join('kecamatan k', 'k.id = d.kecamatan_id', 'left')
                ->where('l.event_perusahaan_id', $ep['id']);

            if ($lowonganFilter > 0) {
                $builder->where('l.lowongan_id', $lowonganFilter);
            }
            if ($pendidikanFilter !== '') {
                $builder->where('pck.pendidikan', $pendidikanFilter);
            }
            if ($jkFilter !== '') {
                $builder->where('pck.jk', $jkFilter);
            }
            if ($statusFilter !== '') {
                $builder->where('l.status', $statusFilter);
            }

            $pelamarList = $builder->orderBy('l.id', 'DESC')->get()->getResultArray();
        }

        return $this->renderView('perusahaan/pelamar', [
            'title'            => 'Daftar & Seleksi Pelamar',
            'ep'               => $ep,
            'pelamarList'      => $pelamarList,
            'lowonganOpts'     => $lowonganOpts,
            'lowonganFilter'   => $lowonganFilter,
            'pendidikanFilter' => $pendidikanFilter,
            'jkFilter'         => $jkFilter,
            'statusFilter'     => $statusFilter,
            'seleksiClosed'    => $this->isSeleksiClosed(),
        ]);
    }

    public function detailPelamar(int $lamaranId)
    {
        $ep = $this->getEventPerusahaan();
        $row = $this->db->table('lamaran l')
            ->select('l.*, low.jabatan, pck.*, u.email, d.nama AS nama_desa, k.nama AS nama_kecamatan')
            ->join('lowongan low', 'low.id = l.lowongan_id')
            ->join('event_peserta eps', 'eps.id = l.event_peserta_id')
            ->join('pencaker pck', 'pck.id = eps.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->join('desa d', 'd.id = pck.desa_id', 'left')
            ->join('kecamatan k', 'k.id = d.kecamatan_id', 'left')
            ->where('l.id', $lamaranId)
            ->where('l.event_perusahaan_id', $ep['id'] ?? 0)
            ->get()
            ->getRowArray();

        if (! $row) {
            return redirect()->to('/perusahaan/pelamar')->with('error', 'Data pelamar tidak ditemukan.');
        }

        $pengalaman = $this->db->table('pengalaman_kerja')->where('pencaker_id', $row['id'])->get()->getResultArray();
        $keahlian   = $this->db->table('keahlian')->where('pencaker_id', $row['id'])->get()->getResultArray();
        $logs       = $this->db->table('lamaran_log')->where('lamaran_id', $lamaranId)->orderBy('id', 'DESC')->get()->getResultArray();

        return $this->renderView('perusahaan/detail_pelamar', [
            'title'         => 'Detail Pelamar: ' . $row['nama'],
            'pelamar'       => $row,
            'lamaranId'     => $lamaranId,
            'pengalaman'    => $pengalaman,
            'keahlian'      => $keahlian,
            'logs'          => $logs,
            'seleksiClosed' => $this->isSeleksiClosed(),
        ]);
    }

    public function updateStatus()
    {
        if ($this->isSeleksiClosed()) {
            return redirect()->back()->with('error', 'Batas waktu seleksi untuk event ini telah berakhir.');
        }

        $ep = $this->getEventPerusahaan();
        $p  = $this->getPerusahaan();
        $lamaranId = (int) $this->request->getPost('lamaran_id');
        $statusBaru = (string) $this->request->getPost('status');
        $jadwalTes  = trim((string) $this->request->getPost('jadwal_tes')) ?: null;
        $lokasiTes  = trim((string) $this->request->getPost('lokasi_tes')) ?: null;
        $catatan    = trim((string) $this->request->getPost('catatan')) ?: null;

        $row = $this->db->table('lamaran l')
            ->select('l.*, low.jabatan, pck.nama AS nama_pelamar, u.email AS email_pelamar')
            ->join('lowongan low', 'low.id = l.lowongan_id')
            ->join('event_peserta eps', 'eps.id = l.event_peserta_id')
            ->join('pencaker pck', 'pck.id = eps.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->where('l.id', $lamaranId)
            ->where('l.event_perusahaan_id', $ep['id'] ?? 0)
            ->get()
            ->getRowArray();

        if (! $row) {
            return redirect()->back()->with('error', 'Lamaran tidak ditemukan.');
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('lamaran')->where('id', $lamaranId)->update([
            'status'     => $statusBaru,
            'jadwal_tes' => $jadwalTes ? date('Y-m-d H:i:s', strtotime($jadwalTes)) : $row['jadwal_tes'],
            'lokasi_tes' => $lokasiTes ?? $row['lokasi_tes'],
            'catatan'    => $catatan,
            'updated_at' => $now,
        ]);

        $this->db->table('lamaran_log')->insert([
            'lamaran_id'  => $lamaranId,
            'status_lama' => $row['status'],
            'status_baru' => $statusBaru,
            'catatan'     => $catatan,
            'user_id'     => session()->get('user_id'),
            'created_at'  => $now,
        ]);

        // Queue email notification to applicant
        $jadwalHtml = '';
        if ($statusBaru === 'PanggilTes' && $jadwalTes) {
            $jadwalHtml = "<p><strong>Jadwal Tes / Interview:</strong> " . date('d M Y H:i', strtotime($jadwalTes)) . " WIB<br>
                           <strong>Lokasi Tes:</strong> " . esc($lokasiTes ?: $p['alamat']) . "</p>";
        }

        JmfService::queueEmail(
            $row['email_pelamar'],
            "Update Status Lamaran: {$row['jabatan']} - {$p['nama']}",
            "<h3>Halo, {$row['nama_pelamar']}</h3>
             <p>Status lamaran Anda untuk posisi <strong>{$row['jabatan']}</strong> di <strong>{$p['nama']}</strong> telah diperbarui menjadi: <strong>{$statusBaru}</strong>.</p>
             {$jadwalHtml}
             <p><strong>Catatan HRD:</strong> " . esc($catatan ?: '-') . "</p>
             <p>Silakan login ke dashboard Job Market Fair UPT BLK Pasuruan untuk melihat detail lengkapnya.</p>"
        );

        return redirect()->back()->with('success', 'Status pelamar diperbarui dan notifikasi email telah dimasukkan ke antrean kirim.');
    }

    public function updateStatusMassal()
    {
        if ($this->isSeleksiClosed()) {
            return redirect()->back()->with('error', 'Batas waktu seleksi telah berakhir.');
        }

        $ep = $this->getEventPerusahaan();
        $p  = $this->getPerusahaan();
        $ids = (array) $this->request->getPost('lamaran_ids');
        $statusBaru = (string) $this->request->getPost('status');
        $jadwalTes  = trim((string) $this->request->getPost('jadwal_tes')) ?: null;
        $lokasiTes  = trim((string) $this->request->getPost('lokasi_tes')) ?: null;
        $catatan    = trim((string) $this->request->getPost('catatan')) ?: null;

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 pelamar terlebih dahulu.');
        }

        $now = date('Y-m-d H:i:s');
        $updated = 0;

        foreach ($ids as $id) {
            $lamaranId = (int) $id;
            $row = $this->db->table('lamaran l')
                ->select('l.*, low.jabatan, pck.nama AS nama_pelamar, u.email AS email_pelamar')
                ->join('lowongan low', 'low.id = l.lowongan_id')
                ->join('event_peserta eps', 'eps.id = l.event_peserta_id')
                ->join('pencaker pck', 'pck.id = eps.pencaker_id')
                ->join('users u', 'u.id = pck.user_id')
                ->where('l.id', $lamaranId)
                ->where('l.event_perusahaan_id', $ep['id'] ?? 0)
                ->get()
                ->getRowArray();

            if (! $row) {
                continue;
            }

            $this->db->table('lamaran')->where('id', $lamaranId)->update([
                'status'     => $statusBaru,
                'jadwal_tes' => $jadwalTes ? date('Y-m-d H:i:s', strtotime($jadwalTes)) : $row['jadwal_tes'],
                'lokasi_tes' => $lokasiTes ?: $row['lokasi_tes'],
                'catatan'    => $catatan ?: $row['catatan'],
                'updated_at' => $now,
            ]);

            $this->db->table('lamaran_log')->insert([
                'lamaran_id'  => $lamaranId,
                'status_lama' => $row['status'],
                'status_baru' => $statusBaru,
                'catatan'     => $catatan ?: 'Update status massal',
                'user_id'     => session()->get('user_id'),
                'created_at'  => $now,
            ]);

            JmfService::queueEmail(
                $row['email_pelamar'],
                "Update Status Lamaran: {$row['jabatan']} - {$p['nama']}",
                "<h3>Halo, {$row['nama_pelamar']}</h3>
                 <p>Status lamaran Anda untuk posisi <strong>{$row['jabatan']}</strong> di <strong>{$p['nama']}</strong> telah diperbarui menjadi: <strong>{$statusBaru}</strong>.</p>
                 <p><strong>Catatan HRD:</strong> " . esc($catatan ?: '-') . "</p>"
            );
            $updated++;
        }

        return redirect()->to('/perusahaan/pelamar')->with('success', "{$updated} pelamar berhasil diperbarui secara massal.");
    }

    public function exportExcel()
    {
        $ep = $this->getEventPerusahaan();
        $p  = $this->getPerusahaan();
        $rows = $this->db->query("
            SELECT l.*, low.jabatan, pck.nik, pck.nama, pck.jk, pck.tgl_lahir, pck.pendidikan, pck.jurusan,
                   pck.hp, pck.alamat, pck.domisili, pck.kota_luar, pck.disabilitas, pck.jenis_disabilitas, pck.link_berkas,
                   k.nama AS nama_kecamatan, d.nama AS nama_desa, u.email
            FROM lamaran l
            JOIN lowongan low ON low.id = l.lowongan_id
            JOIN event_peserta eps ON eps.id = l.event_peserta_id
            JOIN pencaker pck ON pck.id = eps.pencaker_id
            JOIN users u ON u.id = pck.user_id
            LEFT JOIN desa d ON d.id = pck.desa_id
            LEFT JOIN kecamatan k ON k.id = d.kecamatan_id
            WHERE l.event_perusahaan_id = ?
            ORDER BY l.id ASC
        ", [$ep['id'] ?? 0])->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pelamar');

        $headers = ['No', 'Waktu Lamar', 'Jabatan Dilamar', 'NIK', 'Nama Lengkap', 'L/P', 'Tgl Lahir', 'Pendidikan', 'Jurusan', 'No. HP/WA', 'Email', 'Domisili', 'Disabilitas', 'Status Seleksi', 'Link Berkas Gabungan (PDF)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        $rowNum = 2;
        foreach ($rows as $idx => $r) {
            $dom = $r['domisili'] === 'kab_pasuruan'
                ? ($r['nama_desa'] . ', Kec. ' . $r['nama_kecamatan'] . ', Kab. Pasuruan')
                : $r['kota_luar'];

            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $r['created_at']);
            $sheet->setCellValue('C' . $rowNum, $r['jabatan']);
            $sheet->setCellValueExplicit('D' . $rowNum, $r['nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $r['nama']);
            $sheet->setCellValue('F' . $rowNum, $r['jk']);
            $sheet->setCellValue('G' . $rowNum, $r['tgl_lahir']);
            $sheet->setCellValue('H' . $rowNum, $r['pendidikan']);
            $sheet->setCellValue('I' . $rowNum, $r['jurusan']);
            $sheet->setCellValueExplicit('J' . $rowNum, $r['hp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('K' . $rowNum, $r['email']);
            $sheet->setCellValue('L' . $rowNum, $dom);
            $sheet->setCellValue('M' . $rowNum, $r['disabilitas'] ? ('Ya (' . $r['jenis_disabilitas'] . ')') : 'Tidak');
            $sheet->setCellValue('N' . $rowNum, $r['status']);
            $sheet->setCellValue('O' . $rowNum, $r['link_berkas']);
            $rowNum++;
        }

        foreach (range('A', 'O') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        $filename = 'Pelamar-' . url_title($p['nama'] ?? 'Perusahaan', '-', true) . '.xlsx';
        return $this->response
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($excelOutput);
    }

    public function penempatan()
    {
        $ep = $this->getEventPerusahaan();
        $rekap = [];
        if ($ep) {
            $rekap = $this->db->query("
                SELECT l.id, l.jabatan, l.kuota,
                       COUNT(lam.id) AS total_pelamar,
                       SUM(CASE WHEN lam.status = 'LolosBerkas' THEN 1 ELSE 0 END) AS lolos_berkas,
                       SUM(CASE WHEN lam.status = 'PanggilTes' THEN 1 ELSE 0 END) AS panggil_tes,
                       SUM(CASE WHEN lam.status = 'Diterima' THEN 1 ELSE 0 END) AS diterima,
                       SUM(CASE WHEN lam.status = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak
                FROM lowongan l
                LEFT JOIN lamaran lam ON lam.lowongan_id = l.id
                WHERE l.event_perusahaan_id = ?
                GROUP BY l.id
            ", [$ep['id']])->getResultArray();
        }

        return $this->renderView('perusahaan/penempatan', [
            'title' => 'Rekapitulasi Hasil Penempatan Tenaga Kerja',
            'rekap' => $rekap,
        ]);
    }

    public function posterQr()
    {
        $ep = $this->getEventPerusahaan();
        $p  = $this->getPerusahaan();
        if (! $ep) {
            return redirect()->to('/perusahaan')->with('error', 'Booth belum terdaftar.');
        }

        $lowonganList = $this->db->table('lowongan')
            ->where('event_perusahaan_id', $ep['id'])
            ->where('is_active', 1)
            ->get()
            ->getResultArray();

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

    public function survei()
    {
        $event = $this->activeEvent;
        $pertanyaan = [];
        $jawabanMap = [];
        if ($event) {
            $pertanyaan = $this->db->table('survei_pertanyaan')
                ->where('event_id', $event['id'])
                ->where('target', 'perusahaan')
                ->orderBy('urutan', 'ASC')
                ->get()
                ->getResultArray();

            $jRows = $this->db->table('survei_jawaban')
                ->where('user_id', session()->get('user_id'))
                ->get()
                ->getResultArray();
            foreach ($jRows as $j) {
                $jawabanMap[$j['pertanyaan_id']] = $j;
            }
        }

        return $this->renderView('pencaker/survei', [
            'title'      => 'Survei Kepuasan Perusahaan Peserta Job Market Fair',
            'pertanyaan' => $pertanyaan,
            'jawabanMap' => $jawabanMap,
        ]);
    }

    public function saveSurvei()
    {
        $event = $this->activeEvent;
        if (! $event) {
            return redirect()->back();
        }
        $userId = (int) session()->get('user_id');
        $pertanyaan = $this->db->table('survei_pertanyaan')
            ->where('event_id', $event['id'])
            ->where('target', 'perusahaan')
            ->get()
            ->getResultArray();

        $now = date('Y-m-d H:i:s');
        foreach ($pertanyaan as $q) {
            $val = $this->request->getPost('q_' . $q['id']);
            if ($val === null || $val === '') {
                continue;
            }
            $this->db->table('survei_jawaban')->replace([
                'pertanyaan_id' => $q['id'],
                'user_id'       => $userId,
                'nilai'         => $q['tipe'] === 'skala' ? (int) $val : null,
                'jawaban_teks'  => $q['tipe'] === 'teks' ? trim((string) $val) : null,
                'created_at'    => $now,
            ]);
        }

        return redirect()->to('/perusahaan/survei')->with('success', 'Terima kasih! Survei kepuasan perusahaan telah disimpan.');
    }
}
