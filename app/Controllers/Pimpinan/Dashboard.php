<?php

namespace App\Controllers\Pimpinan;

use App\Controllers\BaseController;
use App\Libraries\JmfService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends BaseController
{
    private function collectEventData(int $eventId): array
    {
        $totalPendaftar = (int) $this->db->table('event_peserta ep')
            ->join('pencaker pck', 'pck.id = ep.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->where('ep.event_id', $eventId)
            ->where('u.is_active', 1)
            ->countAllResults();

        $totalHadir = (int) $this->db->table('event_peserta ep')
            ->join('pencaker pck', 'pck.id = ep.pencaker_id')
            ->join('users u', 'u.id = pck.user_id')
            ->where('ep.event_id', $eventId)
            ->where('ep.checkin_at IS NOT NULL')
            ->where('u.is_active', 1)
            ->countAllResults();

        $totalPerusahaan = (int) $this->db->table('event_perusahaan ep')
            ->join('perusahaan p', 'p.id = ep.perusahaan_id')
            ->join('users u', 'u.id = p.user_id')
            ->where('ep.event_id', $eventId)
            ->where('u.is_active', 1)
            ->countAllResults();

        $lowSummary = $this->db->query("
            SELECT COUNT(l.id) AS jml_lowongan, COALESCE(SUM(l.kuota), 0) AS total_kuota
            FROM lowongan l
            JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            JOIN users u ON u.id = p.user_id
            WHERE ep.event_id = ? AND l.is_active = 1 AND u.is_active = 1
        ", [$eventId])->getRowArray();

        $lamSummary = $this->db->query("
            SELECT COUNT(lam.id) AS total_lamaran,
                   SUM(CASE WHEN lam.status = 'Diterima' THEN 1 ELSE 0 END) AS total_diterima,
                   SUM(CASE WHEN lam.status = 'PanggilTes' THEN 1 ELSE 0 END) AS total_panggil
            FROM lamaran lam
            JOIN event_perusahaan ep ON ep.id = lam.event_perusahaan_id
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            JOIN users u_comp ON u_comp.id = p.user_id
            JOIN event_peserta eps ON eps.id = lam.event_peserta_id
            JOIN pencaker pck ON pck.id = eps.pencaker_id
            JOIN users u_pck ON u_pck.id = pck.user_id
            WHERE ep.event_id = ? AND u_comp.is_active = 1 AND u_pck.is_active = 1
        ", [$eventId])->getRowArray();

        $perSesi = $this->db->query("
            SELECT s.nama, s.jam_mulai, s.jam_selesai, s.kuota,
                   COALESCE(SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END), 0) AS terdaftar,
                   COALESCE(SUM(CASE WHEN u.is_active = 1 AND ep.checkin_at IS NOT NULL THEN 1 ELSE 0 END), 0) AS hadir
            FROM event_sesi s
            LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
            LEFT JOIN pencaker pck ON pck.id = ep.pencaker_id
            LEFT JOIN users u ON u.id = pck.user_id
            WHERE s.event_id = ?
            GROUP BY s.id
            ORDER BY s.urutan ASC
        ", [$eventId])->getResultArray();

        $perPerusahaan = $this->db->query("
            SELECT p.nama AS nama_perusahaan, ep.no_booth,
                   COUNT(DISTINCT l.id) AS jml_lowongan,
                   COALESCE(SUM(DISTINCT l.kuota), 0) AS total_kuota,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 THEN 1 ELSE 0 END), 0) AS total_pelamar,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'LolosBerkas' THEN 1 ELSE 0 END), 0) AS lolos_berkas,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'PanggilTes' THEN 1 ELSE 0 END), 0) AS panggil_tes,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'Diterima' THEN 1 ELSE 0 END), 0) AS diterima
            FROM event_perusahaan ep
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            JOIN users u_comp ON u_comp.id = p.user_id
            LEFT JOIN lowongan l ON l.event_perusahaan_id = ep.id AND l.is_active = 1
            LEFT JOIN lamaran lam ON lam.lowongan_id = l.id
            LEFT JOIN event_peserta eps ON eps.id = lam.event_peserta_id
            LEFT JOIN pencaker pck ON pck.id = eps.pencaker_id
            LEFT JOIN users u_pck ON u_pck.id = pck.user_id
            WHERE ep.event_id = ? AND u_comp.is_active = 1
            GROUP BY ep.id
            ORDER BY total_pelamar DESC, ep.no_booth ASC
        ", [$eventId])->getResultArray();

        // Demografi Gender
        $demoGender = $this->db->query("
            SELECT pck.jk, COUNT(ep.id) AS jml
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            JOIN users u ON u.id = pck.user_id
            WHERE ep.event_id = ? AND u.is_active = 1
            GROUP BY pck.jk
        ", [$eventId])->getResultArray();

        // Demografi Pendidikan
        $demoPendidikan = $this->db->query("
            SELECT pck.pendidikan, COUNT(ep.id) AS jml
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            JOIN users u ON u.id = pck.user_id
            WHERE ep.event_id = ? AND u.is_active = 1
            GROUP BY pck.pendidikan
            ORDER BY FIELD(pck.pendidikan, 'SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2')
        ", [$eventId])->getResultArray();

        // Demografi Wilayah (Kecamatan Kab. Pasuruan + Luar Daerah)
        $demoWilayah = $this->db->query("
            SELECT CASE WHEN pck.domisili = 'kab_pasuruan' THEN CONCAT('Kec. ', COALESCE(k.nama, 'Lainnya'))
                        ELSE CONCAT('Luar: ', COALESCE(pck.kota_luar, 'Lainnya'))
                   END AS wilayah,
                   COUNT(ep.id) AS jml
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            JOIN users u ON u.id = pck.user_id
            LEFT JOIN desa d ON d.id = pck.desa_id
            LEFT JOIN kecamatan k ON k.id = d.kecamatan_id
            WHERE ep.event_id = ? AND u.is_active = 1
            GROUP BY wilayah
            ORDER BY jml DESC
        ", [$eventId])->getResultArray();

        // Demografi Kelompok Usia & Disabilitas & Alumni BLK
        $demoKhusus = $this->db->query("
            SELECT SUM(CASE WHEN pck.disabilitas = 1 THEN 1 ELSE 0 END) AS disabilitas,
                   SUM(CASE WHEN EXISTS (SELECT 1 FROM keahlian kh WHERE kh.pencaker_id = pck.id AND kh.alumni_blk = 1) THEN 1 ELSE 0 END) AS alumni_blk,
                   SUM(CASE WHEN TIMESTAMPDIFF(YEAR, pck.tgl_lahir, CURDATE()) <= 20 THEN 1 ELSE 0 END) AS usia_20,
                   SUM(CASE WHEN TIMESTAMPDIFF(YEAR, pck.tgl_lahir, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS usia_21_25,
                   SUM(CASE WHEN TIMESTAMPDIFF(YEAR, pck.tgl_lahir, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS usia_26_30,
                   SUM(CASE WHEN TIMESTAMPDIFF(YEAR, pck.tgl_lahir, CURDATE()) > 30 THEN 1 ELSE 0 END) AS usia_30_plus
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            JOIN users u ON u.id = pck.user_id
            WHERE ep.event_id = ? AND u.is_active = 1
        ", [$eventId])->getRowArray();

        // Grafik Check-in per Jam
        $perJam = $this->db->query("
            SELECT DATE_FORMAT(ep.checkin_at, '%H:00') AS jam, COUNT(ep.id) AS jml
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            JOIN users u ON u.id = pck.user_id
            WHERE ep.event_id = ? AND ep.checkin_at IS NOT NULL AND u.is_active = 1
            GROUP BY jam
            ORDER BY jam ASC
        ", [$eventId])->getResultArray();

        // Rekap Survei
        $surveiRekap = $this->db->query("
            SELECT sp.target, sp.pertanyaan, sp.tipe,
                   COUNT(sj.id) AS responden,
                   ROUND(AVG(sj.nilai), 2) AS rata_nilai
            FROM survei_pertanyaan sp
            LEFT JOIN survei_jawaban sj ON sj.pertanyaan_id = sp.id
            WHERE sp.event_id = ?
            GROUP BY sp.id
            ORDER BY sp.target ASC, sp.urutan ASC
        ", [$eventId])->getResultArray();

        return [
            'totalPendaftar' => $totalPendaftar,
            'totalHadir'     => $totalHadir,
            'totalPerusahaan'=> $totalPerusahaan,
            'totalLowongan'  => (int) ($lowSummary['jml_lowongan'] ?? 0),
            'totalKuota'     => (int) ($lowSummary['total_kuota'] ?? 0),
            'totalLamaran'   => (int) ($lamSummary['total_lamaran'] ?? 0),
            'totalDiterima'  => (int) ($lamSummary['total_diterima'] ?? 0),
            'totalPanggil'   => (int) ($lamSummary['total_panggil'] ?? 0),
            'perSesi'        => $perSesi,
            'perPerusahaan'  => $perPerusahaan,
            'demoGender'     => $demoGender,
            'demoPendidikan' => $demoPendidikan,
            'demoWilayah'    => $demoWilayah,
            'demoKhusus'     => $demoKhusus,
            'perJam'         => $perJam,
            'surveiRekap'    => $surveiRekap,
        ];
    }

    public function index()
    {
        $event = $this->activeEvent;
        $data = $event ? $this->collectEventData($event['id']) : [];

        return $this->renderView('pimpinan/dashboard', [
            'title' => 'Dashboard Monitoring Real-Time Job Market Fair',
            'stats' => $data,
        ]);
    }

    public function realtimeStats()
    {
        $event = $this->activeEvent;
        if (! $event) {
            return $this->response->setJSON(['ok' => false]);
        }
        return $this->response->setJSON([
            'ok'    => true,
            'waktu' => date('H:i:s'),
            'stats' => $this->collectEventData($event['id']),
        ]);
    }

    private function getRekapPenempatan(): array
    {
        $event = $this->activeEvent;
        $params = [];
        $whereEvent = 'WHERE u_comp.is_active = 1';
        if ($event) {
            $whereEvent .= ' AND ep.event_id = ?';
            $params[] = $event['id'];
        }

        return $this->db->query("
            SELECT l.id, l.jabatan, COALESCE(l.kuota, 0) AS kuota,
                   p.nama AS nama_perusahaan, ep.no_booth,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 THEN 1 ELSE 0 END), 0) AS total_pelamar,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'LolosBerkas' THEN 1 ELSE 0 END), 0) AS lolos_berkas,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'PanggilTes' THEN 1 ELSE 0 END), 0) AS panggil_tes,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'Diterima' THEN 1 ELSE 0 END), 0) AS diterima,
                   COALESCE(SUM(CASE WHEN u_pck.is_active = 1 AND lam.status = 'Ditolak' THEN 1 ELSE 0 END), 0) AS ditolak
            FROM event_perusahaan ep
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            JOIN users u_comp ON u_comp.id = p.user_id
            LEFT JOIN lowongan l ON l.event_perusahaan_id = ep.id AND l.is_active = 1
            LEFT JOIN lamaran lam ON lam.lowongan_id = l.id
            LEFT JOIN event_peserta eps ON eps.id = lam.event_peserta_id
            LEFT JOIN pencaker pck ON pck.id = eps.pencaker_id
            LEFT JOIN users u_pck ON u_pck.id = pck.user_id
            {$whereEvent}
            GROUP BY ep.id, l.id
            ORDER BY ep.no_booth ASC, p.nama ASC, l.id ASC
        ", $params)->getResultArray();
    }

    public function penempatan()
    {
        return $this->renderView('pimpinan/penempatan', [
            'title' => 'Rekapitulasi Hasil Penempatan Tenaga Kerja',
            'rekap' => $this->getRekapPenempatan(),
        ]);
    }

    public function exportPenempatanExcel()
    {
        $event = $this->activeEvent;
        $rekap = $this->getRekapPenempatan();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Penempatan');

        $sheet->setCellValue('A1', 'REKAPITULASI HASIL SELEKSI & PENEMPATAN TENAGA KERJA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->setCellValue('A2', strtoupper($event['nama'] ?? 'JOB MARKET FAIR UPT BLK PASURUAN'));
        $sheet->getStyle('A2')->getFont()->setBold(true);

        $headers = [
            'No',
            'Booth',
            'Perusahaan',
            'Lowongan Jabatan',
            'Kuota Kebutuhan',
            'Total Pelamar',
            'Lolos Berkas',
            'Panggil Tes',
            'Diterima Bekerja',
            'Ditolak',
            '% Penempatan',
        ];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $col++;
        }

        $rNum = 5;
        $totKuota   = 0;
        $totPelamar = 0;
        $totLolos   = 0;
        $totPanggil = 0;
        $totTerima  = 0;
        $totTolak   = 0;

        foreach ($rekap as $idx => $r) {
            $kuota   = (int) ($r['kuota'] ?? 0);
            $pelamar = (int) ($r['total_pelamar'] ?? 0);
            $lolos   = (int) ($r['lolos_berkas'] ?? 0);
            $panggil = (int) ($r['panggil_tes'] ?? 0);
            $terima  = (int) ($r['diterima'] ?? 0);
            $tolak   = (int) ($r['ditolak'] ?? 0);
            $persen  = $kuota > 0 ? round(($terima / $kuota) * 100, 1) : 0;

            $totKuota   += $kuota;
            $totPelamar += $pelamar;
            $totLolos   += $lolos;
            $totPanggil += $panggil;
            $totTerima  += $terima;
            $totTolak   += $tolak;

            $sheet->setCellValue('A' . $rNum, $idx + 1);
            $sheet->setCellValue('B' . $rNum, $r['no_booth'] ?? '-');
            $sheet->setCellValue('C' . $rNum, $r['nama_perusahaan']);
            $sheet->setCellValue('D' . $rNum, ! empty($r['jabatan']) ? $r['jabatan'] : 'Belum ada lowongan');
            $sheet->setCellValue('E' . $rNum, $kuota);
            $sheet->setCellValue('F' . $rNum, $pelamar);
            $sheet->setCellValue('G' . $rNum, $lolos);
            $sheet->setCellValue('H' . $rNum, $panggil);
            $sheet->setCellValue('I' . $rNum, $terima);
            $sheet->setCellValue('J' . $rNum, $tolak);
            $sheet->setCellValue('K' . $rNum, $persen . '%');
            $rNum++;
        }

        $totPersen = $totKuota > 0 ? round(($totTerima / $totKuota) * 100, 1) : 0;
        $sheet->setCellValue('A' . $rNum, '');
        $sheet->setCellValue('B' . $rNum, '');
        $sheet->setCellValue('C' . $rNum, '');
        $sheet->setCellValue('D' . $rNum, 'Total Keseluruhan');
        $sheet->setCellValue('E' . $rNum, $totKuota);
        $sheet->setCellValue('F' . $rNum, $totPelamar);
        $sheet->setCellValue('G' . $rNum, $totLolos);
        $sheet->setCellValue('H' . $rNum, $totPanggil);
        $sheet->setCellValue('I' . $rNum, $totTerima);
        $sheet->setCellValue('J' . $rNum, $totTolak);
        $sheet->setCellValue('K' . $rNum, $totPersen . '%');
        $sheet->getStyle('A' . $rNum . ':K' . $rNum)->getFont()->setBold(true);

        foreach (range('A', 'K') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return $this->response
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="Rekap-Penempatan-JMF-BLK-Pasuruan.xlsx"')
            ->setBody($excelOutput);
    }

    public function laporan()
    {
        $event = $this->activeEvent;
        $data = $event ? $this->collectEventData($event['id']) : [];

        return $this->renderView('pimpinan/laporan', [
            'title' => 'Laporan Komprehensif, Demografi & Penempatan Kerja',
            'stats' => $data,
        ]);
    }

    public function exportPdf(string $jenis = 'lengkap')
    {
        $event = $this->activeEvent;
        $data  = $event ? $this->collectEventData($event['id']) : [];

        $html = view('pdf/laporan_resmi', [
            'pengaturan' => $this->pengaturan,
            'event'      => $event,
            'stats'      => $data,
            'jenis'      => $jenis,
        ]);

        return JmfService::renderPdf($html, 'Laporan-Resmi-JMF-BLK-Pasuruan-' . date('Y') . '.pdf', 'A4', 'portrait');
    }

    public function exportExcel(string $jenis = 'lengkap')
    {
        $event = $this->activeEvent;
        $data  = $event ? $this->collectEventData($event['id']) : [];

        $spreadsheet = new Spreadsheet();

        // Sheet 1: Ringkasan & Per Perusahaan
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Rekap Perusahaan');
        $sheet1->setCellValue('A1', 'LAPORAN PELAKSANAAN ' . strtoupper($event['nama'] ?? 'JOB MARKET FAIR'));
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(13);

        $headers = ['No', 'Booth', 'Nama Perusahaan', 'Jml Lowongan', 'Kuota Kebutuhan', 'Total Pelamar', 'Lolos Berkas', 'Panggil Tes', 'Diterima Kerja'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet1->setCellValue($col . '3', $h);
            $sheet1->getStyle($col . '3')->getFont()->setBold(true);
            $col++;
        }

        $rNum = 4;
        foreach ($data['perPerusahaan'] ?? [] as $idx => $pr) {
            $sheet1->setCellValue('A' . $rNum, $idx + 1);
            $sheet1->setCellValue('B' . $rNum, $pr['no_booth']);
            $sheet1->setCellValue('C' . $rNum, $pr['nama_perusahaan']);
            $sheet1->setCellValue('D' . $rNum, $pr['jml_lowongan']);
            $sheet1->setCellValue('E' . $rNum, $pr['total_kuota']);
            $sheet1->setCellValue('F' . $rNum, $pr['total_pelamar']);
            $sheet1->setCellValue('G' . $rNum, $pr['lolos_berkas']);
            $sheet1->setCellValue('H' . $rNum, $pr['panggil_tes']);
            $sheet1->setCellValue('I' . $rNum, $pr['diterima']);
            $rNum++;
        }
        foreach (range('A', 'I') as $c) {
            $sheet1->getColumnDimension($c)->setAutoSize(true);
        }

        // Sheet 2: Demografi & Sesi
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Demografi & Sesi');
        $sheet2->setCellValue('A1', 'DEMOGRAFI WILAYAH PESERTA');
        $sheet2->getStyle('A1')->getFont()->setBold(true);
        $sheet2->setCellValue('A2', 'Wilayah (Kecamatan / Kab/Kota Luar)');
        $sheet2->setCellValue('B2', 'Jumlah Peserta');
        $rNum = 3;
        foreach ($data['demoWilayah'] ?? [] as $dw) {
            $sheet2->setCellValue('A' . $rNum, $dw['wilayah']);
            $sheet2->setCellValue('B' . $rNum, $dw['jml']);
            $rNum++;
        }
        $sheet2->getColumnDimension('A')->setAutoSize(true);
        $sheet2->getColumnDimension('B')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return $this->response
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="Rekap-Laporan-JMF-BLK-Pasuruan.xlsx"')
            ->setBody($excelOutput);
    }
}
