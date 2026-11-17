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
        $totalPendaftar = (int) $this->db->table('event_peserta')->where('event_id', $eventId)->countAllResults();
        $totalHadir     = (int) $this->db->table('event_peserta')->where('event_id', $eventId)->where('checkin_at IS NOT NULL')->countAllResults();
        $totalPerusahaan= (int) $this->db->table('event_perusahaan')->where('event_id', $eventId)->countAllResults();

        $lowSummary = $this->db->query("
            SELECT COUNT(l.id) AS jml_lowongan, COALESCE(SUM(l.kuota), 0) AS total_kuota
            FROM lowongan l
            JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
            WHERE ep.event_id = ?
        ", [$eventId])->getRowArray();

        $lamSummary = $this->db->query("
            SELECT COUNT(lam.id) AS total_lamaran,
                   SUM(CASE WHEN lam.status = 'Diterima' THEN 1 ELSE 0 END) AS total_diterima,
                   SUM(CASE WHEN lam.status = 'PanggilTes' THEN 1 ELSE 0 END) AS total_panggil
            FROM lamaran lam
            JOIN event_perusahaan ep ON ep.id = lam.event_perusahaan_id
            WHERE ep.event_id = ?
        ", [$eventId])->getRowArray();

        $perSesi = $this->db->query("
            SELECT s.nama, s.jam_mulai, s.jam_selesai, s.kuota,
                   COUNT(ep.id) AS terdaftar,
                   SUM(CASE WHEN ep.checkin_at IS NOT NULL THEN 1 ELSE 0 END) AS hadir
            FROM event_sesi s
            LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
            WHERE s.event_id = ?
            GROUP BY s.id
            ORDER BY s.urutan ASC
        ", [$eventId])->getResultArray();

        $perPerusahaan = $this->db->query("
            SELECT p.nama AS nama_perusahaan, ep.no_booth,
                   COUNT(DISTINCT l.id) AS jml_lowongan,
                   COALESCE(SUM(DISTINCT l.kuota), 0) AS total_kuota,
                   COUNT(lam.id) AS total_pelamar,
                   SUM(CASE WHEN lam.status = 'LolosBerkas' THEN 1 ELSE 0 END) AS lolos_berkas,
                   SUM(CASE WHEN lam.status = 'PanggilTes' THEN 1 ELSE 0 END) AS panggil_tes,
                   SUM(CASE WHEN lam.status = 'Diterima' THEN 1 ELSE 0 END) AS diterima
            FROM event_perusahaan ep
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            LEFT JOIN lowongan l ON l.event_perusahaan_id = ep.id
            LEFT JOIN lamaran lam ON lam.lowongan_id = l.id
            WHERE ep.event_id = ?
            GROUP BY ep.id
            ORDER BY total_pelamar DESC, ep.no_booth ASC
        ", [$eventId])->getResultArray();

        // Demografi Gender
        $demoGender = $this->db->query("
            SELECT pck.jk, COUNT(ep.id) AS jml
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            WHERE ep.event_id = ?
            GROUP BY pck.jk
        ", [$eventId])->getResultArray();

        // Demografi Pendidikan
        $demoPendidikan = $this->db->query("
            SELECT pck.pendidikan, COUNT(ep.id) AS jml
            FROM event_peserta ep
            JOIN pencaker pck ON pck.id = ep.pencaker_id
            WHERE ep.event_id = ?
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
            LEFT JOIN desa d ON d.id = pck.desa_id
            LEFT JOIN kecamatan k ON k.id = d.kecamatan_id
            WHERE ep.event_id = ?
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
            WHERE ep.event_id = ?
        ", [$eventId])->getRowArray();

        // Grafik Check-in per Jam
        $perJam = $this->db->query("
            SELECT DATE_FORMAT(checkin_at, '%H:00') AS jam, COUNT(id) AS jml
            FROM event_peserta
            WHERE event_id = ? AND checkin_at IS NOT NULL
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

    public function penempatan()
    {
        $event = $this->activeEvent;
        $params = [];
        $whereEvent = '';
        if ($event) {
            $whereEvent = 'WHERE ep.event_id = ?';
            $params[] = $event['id'];
        }

        $rekap = $this->db->query("
            SELECT l.id, l.jabatan, COALESCE(l.kuota, 0) AS kuota,
                   p.nama AS nama_perusahaan, ep.no_booth,
                   COUNT(lam.id) AS total_pelamar,
                   SUM(CASE WHEN lam.status = 'LolosBerkas' THEN 1 ELSE 0 END) AS lolos_berkas,
                   SUM(CASE WHEN lam.status = 'PanggilTes' THEN 1 ELSE 0 END) AS panggil_tes,
                   SUM(CASE WHEN lam.status = 'Diterima' THEN 1 ELSE 0 END) AS diterima,
                   SUM(CASE WHEN lam.status = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak
            FROM event_perusahaan ep
            JOIN perusahaan p ON p.id = ep.perusahaan_id
            LEFT JOIN lowongan l ON l.event_perusahaan_id = ep.id
            LEFT JOIN lamaran lam ON lam.lowongan_id = l.id
            {$whereEvent}
            GROUP BY ep.id, l.id
            ORDER BY ep.no_booth ASC, p.nama ASC, l.id ASC
        ", $params)->getResultArray();

        return $this->renderView('pimpinan/penempatan', [
            'title' => 'Rekapitulasi Hasil Penempatan Tenaga Kerja',
            'rekap' => $rekap,
        ]);
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
