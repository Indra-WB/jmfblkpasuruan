<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Poster QR Booth <?= esc($ep['no_booth']) ?></title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #0f172a; text-align: center; margin: 20px; }
        .poster { border: 4px solid #0f172a; border-radius: 16px; padding: 25px; }
        .instansi { font-size: 13px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .event-title { font-size: 20px; font-weight: bold; margin: 4px 0 15px; }
        .booth-badge { display: inline-block; background: #0f172a; color: #facc15; font-size: 24px; font-weight: bold; padding: 8px 28px; border-radius: 8px; margin-bottom: 12px; }
        .comp-name { font-size: 24px; font-weight: bold; margin: 5px 0; color: #0f172a; }
        .comp-sector { font-size: 14px; color: #475569; margin-bottom: 18px; }
        .qr-wrap { border: 2px dashed #1e3a8a; border-radius: 12px; padding: 15px; display: inline-block; margin-bottom: 15px; }
        .lowongan-box { text-align: left; margin-top: 15px; border-top: 2px solid #e2e8f0; padding-top: 12px; }
        .lowongan-box h4 { margin: 0 0 8px; font-size: 14px; text-transform: uppercase; }
        table.tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
        table.tbl th, table.tbl td { border: 1px solid #cbd5e1; padding: 6px 8px; }
        table.tbl th { background: #f1f5f9; }
    </style>
</head>
<body>
<div class="poster">
    <div class="instansi"><?= esc($pengaturan['nama_instansi'] ?? 'UPT Balai Latihan Kerja Pasuruan') ?></div>
    <div class="event-title"><?= esc($event['nama'] ?? 'JOB MARKET FAIR') ?></div>

    <div class="booth-badge">BOOTH <?= esc($ep['no_booth']) ?></div>
    <div class="comp-name"><?= esc($perusahaan['nama']) ?></div>
    <div class="comp-sector"><?= esc($perusahaan['bidang_usaha']) ?></div>

    <div class="qr-wrap">
        <img src="<?= $qrDataUri ?>" style="width: 240px; height: 240px;"><br>
        <strong style="font-size: 14px;">SCAN UNTUK MELAMAR KERJA DI PERUSAHAAN INI</strong><br>
        <span style="font-size: 11px; color: #475569;">Kode Booth: <?= esc($ep['qr_token']) ?></span>
    </div>

    <div style="font-size: 12px; background: #fef3c7; padding: 8px; border-radius: 6px; margin-bottom: 10px;">
        <strong>Syarat Melamar:</strong> Pastikan Anda sudah melakukan <strong>Check-in di Meja Registrasi</strong>. Maksimal <strong>1 lowongan jabatan per perusahaan</strong>.
    </div>

    <div class="lowongan-box">
        <h4>Formasi Jabatan yang Dibuka:</h4>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Posisi Jabatan</th>
                    <th>Min. Pendidikan</th>
                    <th>Gender</th>
                    <th>Kuota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lowonganList as $l): ?>
                    <tr>
                        <td><strong><?= esc($l['jabatan']) ?></strong></td>
                        <td><?= esc($l['pendidikan_min']) ?></td>
                        <td><?= esc($l['jenis_kelamin']) ?></td>
                        <td><?= (int)$l['kuota'] ?> orang</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
