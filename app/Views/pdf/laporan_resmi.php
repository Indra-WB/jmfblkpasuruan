<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi Job Market Fair BLK Pasuruan</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #0f172a; margin: 15px 25px; }
        .kop { border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; text-align: center; }
        .kop h3 { margin: 0; font-size: 13px; font-weight: normal; text-transform: uppercase; }
        .kop h2 { margin: 2px 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .kop p { margin: 0; font-size: 10px; }
        .judul { text-align: center; margin-bottom: 15px; }
        .judul h4 { margin: 0; font-size: 13px; text-decoration: underline; text-transform: uppercase; }
        table.tbl { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.tbl th, table.tbl td { border: 1px solid #334155; padding: 5px 7px; }
        table.tbl th { background: #e2e8f0; text-align: center; }
        .ttd { width: 100%; margin-top: 25px; }
    </style>
</head>
<body>
<div class="kop">
    <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
    <h3><?= esc($pengaturan['sub_instansi'] ?? 'DINAS TENAGA KERJA DAN TRANSMIGRASI') ?></h3>
    <h2><?= esc($pengaturan['nama_instansi'] ?? 'UPT BALAI LATIHAN KERJA PASURUAN') ?></h2>
    <p><?= esc($pengaturan['alamat_instansi'] ?? '') ?> | Telp: <?= esc($pengaturan['telp_instansi'] ?? '') ?> | Email: <?= esc($pengaturan['email_instansi'] ?? '') ?></p>
</div>

<div class="judul">
    <h4>LAPORAN PELAKSANAAN DAN PENEMPATAN TENAGA KERJA</h4>
    <div><strong><?= esc(strtoupper($event['nama'] ?? 'JOB MARKET FAIR')) ?></strong></div>
    <div>Tanggal Pelaksanaan: <?= $event ? esc(date('d F Y', strtotime($event['tanggal']))) : '-' ?></div>
</div>

<h4>A. Ringkasan Eksekutif</h4>
<table class="tbl">
    <tr>
        <th style="width:25%;">Total Pendaftar Online</th>
        <th style="width:25%;">Total Hadir (Check-in)</th>
        <th style="width:25%;">Perusahaan & Kuota Lowongan</th>
        <th style="width:25%;">Lamaran & Diterima Kerja</th>
    </tr>
    <tr>
        <td style="text-align:center;"><strong><?= number_format($stats['totalPendaftar'] ?? 0) ?></strong> orang</td>
        <td style="text-align:center;"><strong><?= number_format($stats['totalHadir'] ?? 0) ?></strong> orang</td>
        <td style="text-align:center;"><strong><?= number_format($stats['totalPerusahaan'] ?? 0) ?></strong> PT (Kuota: <strong><?= number_format($stats['totalKuota'] ?? 0) ?></strong>)</td>
        <td style="text-align:center;"><strong><?= number_format($stats['totalLamaran'] ?? 0) ?></strong> lamaran / <strong><?= number_format($stats['totalDiterima'] ?? 0) ?></strong> diterima</td>
    </tr>
</table>

<h4>B. Rekapitulasi Pelamar dan Penempatan per Perusahaan</h4>
<table class="tbl">
    <thead>
        <tr>
            <th>No</th>
            <th>Booth</th>
            <th>Nama Perusahaan</th>
            <th>Lowongan</th>
            <th>Kuota</th>
            <th>Pelamar</th>
            <th>Lolos Berkas</th>
            <th>Panggil Tes</th>
            <th>Diterima</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats['perPerusahaan'] ?? [] as $idx => $pr): ?>
            <tr>
                <td style="text-align:center;"><?= $idx + 1 ?></td>
                <td style="text-align:center;"><?= esc($pr['no_booth']) ?></td>
                <td><?= esc($pr['nama_perusahaan']) ?></td>
                <td style="text-align:center;"><?= (int)$pr['jml_lowongan'] ?></td>
                <td style="text-align:center;"><?= (int)$pr['total_kuota'] ?></td>
                <td style="text-align:center;"><?= (int)$pr['total_pelamar'] ?></td>
                <td style="text-align:center;"><?= (int)$pr['lolos_berkas'] ?></td>
                <td style="text-align:center;"><?= (int)$pr['panggil_tes'] ?></td>
                <td style="text-align:center;"><strong><?= (int)$pr['diterima'] ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4>C. Rekapitulasi Kehadiran per Sesi Kedatangan</h4>
<table class="tbl">
    <thead>
        <tr>
            <th>Nama Sesi</th>
            <th>Jam Sesi</th>
            <th>Kuota Sesi</th>
            <th>Peserta Terdaftar</th>
            <th>Peserta Hadir (Check-in)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats['perSesi'] ?? [] as $s): ?>
            <tr>
                <td><?= esc($s['nama']) ?></td>
                <td style="text-align:center;"><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_selesai'],0,5) ?> WIB</td>
                <td style="text-align:center;"><?= (int)$s['kuota'] ?></td>
                <td style="text-align:center;"><?= (int)$s['terdaftar'] ?></td>
                <td style="text-align:center;"><strong><?= (int)$s['hadir'] ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="ttd">
    <tr>
        <td style="width:60%;"></td>
        <td style="width:40%; text-align:center;">
            Pasuruan, <?= date('d F Y') ?><br>
            Kepala <?= esc($pengaturan['nama_instansi'] ?? 'UPT BLK Pasuruan') ?><br><br><br><br><br>
            <strong><u><?= esc($pengaturan['kepala_blk'] ?? '') ?></u></strong><br>
            NIP. <?= esc($pengaturan['nip_kepala_blk'] ?? '') ?>
        </td>
    </tr>
</table>
</body>
</html>
