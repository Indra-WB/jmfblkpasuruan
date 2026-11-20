<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta <?= esc($kepesertaan['no_registrasi']) ?></title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 10px;
        }

        .card-box {
            border: 2px solid #0f172a;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background: #0f172a;
            color: #ffffff;
            padding: 12px 15px;
            text-align: center;
        }

        .header h2 {
            margin: 0 0 3px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 10px;
            color: #cbd5e1;
        }

        .content {
            padding: 15px;
        }

        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.info td {
            padding: 4px 6px;
            vertical-align: top;
        }

        table.info td.lbl {
            width: 32%;
            color: #64748b;
            font-weight: bold;
        }

        .sesi-banner {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e3a8a;
            padding: 8px 10px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .qr-section {
            text-align: center;
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
        }

        .foto-box {
            width: 70px;
            height: 90px;
            border: 1px solid #94a3b8;
            text-align: center;
            line-height: 90px;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="card-box">
        <div class="header">
            <h2>KARTU PESERTA JOB MARKET FAIR</h2>
            <p><?= esc($pengaturan['nama_instansi'] ?? 'UPT Balai Latihan Kerja Pasuruan') ?></p>
            <p><?= esc($event['nama'] ?? '') ?></p>
        </div>
        <div class="content">
            <?php if (!empty($event['sesi_aktif']) && !empty($kepesertaan['nama_sesi'])): ?>
                <div class="sesi-banner">
                    JADWAL KEDATANGAN: <?= esc(strtoupper($kepesertaan['nama_sesi'])) ?><br>
                    <?= esc(date('d M Y', strtotime($kepesertaan['tgl_sesi']))) ?> | Pukul
                    <?= esc(substr($kepesertaan['jam_mulai'], 0, 5)) ?> -
                    <?= esc(substr($kepesertaan['jam_selesai'], 0, 5)) ?> WIB
                </div>
            <?php endif; ?>

            <table style="width:100%; margin-bottom:8px;">
                <tr>
                    <td style="width:78%; vertical-align:top;">
                        <table class="info">
                            <tr>
                                <td class="lbl">No. Registrasi</td>
                                <td>: <strong><?= esc($kepesertaan['no_registrasi']) ?></strong></td>
                            </tr>
                            <tr>
                                <td class="lbl">NIK KTP</td>
                                <td>: <?= esc($pencaker['nik']) ?></td>
                            </tr>
                            <tr>
                                <td class="lbl">Nama Lengkap</td>
                                <td>: <strong><?= esc($pencaker['nama']) ?></strong></td>
                            </tr>
                            <tr>
                                <td class="lbl">Pendidikan</td>
                                <td>: <?= esc($pencaker['pendidikan']) ?> - <?= esc($pencaker['jurusan']) ?></td>
                            </tr>
                            <tr>
                                <td class="lbl">Domisili</td>
                                <td>:
                                    <?= $pencaker['domisili'] === 'kab_pasuruan' ? esc(($pencaker['nama_desa'] ?? '') . ', Kec. ' . ($pencaker['nama_kecamatan'] ?? '') . ', Kab. Pasuruan') : esc($pencaker['kota_luar']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="lbl">No. HP / WA</td>
                                <td>: <?= esc($pencaker['hp']) ?></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:22%; text-align:right; vertical-align:top;">
                        <?php if (!empty($fotoBase64)): ?>
                            <img src="<?= $fotoBase64 ?>"
                                style="width:70px;height:90px;object-fit:cover;border:1px solid #334155;">
                        <?php else: ?>
                            <div class="foto-box">PAS FOTO</div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div class="qr-section">
                <img src="<?= $qrDataUri ?>" style="width: 155px; height: 155px;">
                <div style="font-weight:bold; font-size:12px; margin-top:4px;"><?= esc($kepesertaan['qr_token']) ?>
                </div>
                <p style="font-size:9.5px; color:#475569; margin:6px 0 0;">
                    1. Tunjukkan QR Code ini di <strong>Meja Registrasi</strong> saat tiba di lokasi untuk Check-in.<br>
                    2. Setelah Check-in, gunakan kamera HP Anda untuk memindai QR Code di Booth Perusahaan yang ingin
                    dilamar. Pastikan Anda sudah melengkapi Biodata, Pas Foto & Link Berkas Lamaran.
                </p>
            </div>
        </div>
    </div>
</body>

</html>