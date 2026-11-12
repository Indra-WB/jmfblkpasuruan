<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<section class="hero-blk py-5">
    <div class="container py-lg-3">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3 rounded-pill">
                    <i class="bi bi-stars me-1"></i> EVENT RESMI UPT BLK PASURUAN
                </span>
                <h1 class="display-5 fw-extrabold mb-3">
                    <?= esc($activeEvent['nama'] ?? 'Job Market Fair UPT BLK Pasuruan') ?>
                </h1>
                <p class="lead text-white-50 mb-4">
                    <?= esc($activeEvent['deskripsi'] ?? 'Temukan peluang karir terbaik di puluhan perusahaan industri Jawa Timur. Daftar online gratis, dapatkan Kartu QR, dan lamar langsung di booth perusahaan.') ?>
                </p>

                <?php if ($activeEvent): ?>
                    <div class="d-flex flex-wrap gap-3 mb-4 text-white">
                        <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2">
                            <small class="d-block text-white-50">Tanggal Pelaksanaan</small>
                            <strong><i class="bi bi-calendar3 text-warning me-1"></i> <?= esc(date('d M Y', strtotime($activeEvent['tanggal']))) ?></strong>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2">
                            <small class="d-block text-white-50">Jam Operasional</small>
                            <strong><i class="bi bi-clock text-warning me-1"></i> <?= esc(substr($activeEvent['jam_mulai'], 0, 5)) ?> - <?= esc(substr($activeEvent['jam_selesai'], 0, 5)) ?> WIB</strong>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2">
                            <small class="d-block text-white-50">Lokasi Acara</small>
                            <strong><i class="bi bi-geo-alt text-warning me-1"></i> <?= esc($activeEvent['lokasi']) ?></strong>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('daftar') ?>" class="btn btn-warning btn-lg fw-bold px-4">
                        <i class="bi bi-qr-code me-1"></i> Daftar & Ambil Kartu QR
                    </a>
                    <a href="<?= base_url('lowongan') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-search me-1"></i> Lihat Lowongan (<?= number_format($stats['lowongan']) ?>)
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white text-dark rounded-4 p-4 shadow">
                            <div class="text-primary fs-2 mb-1"><i class="bi bi-buildings-fill"></i></div>
                            <h2 class="fw-bold mb-0"><?= number_format($stats['perusahaan']) ?></h2>
                            <div class="text-muted small">Perusahaan Peserta</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white text-dark rounded-4 p-4 shadow">
                            <div class="text-success fs-2 mb-1"><i class="bi bi-briefcase-fill"></i></div>
                            <h2 class="fw-bold mb-0"><?= number_format($stats['lowongan']) ?></h2>
                            <div class="text-muted small">Formasi Jabatan</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white text-dark rounded-4 p-4 shadow">
                            <div class="text-warning fs-2 mb-1"><i class="bi bi-person-check-fill"></i></div>
                            <h2 class="fw-bold mb-0"><?= number_format($stats['kuota']) ?></h2>
                            <div class="text-muted small">Kuota Kebutuhan Tenaga Kerja</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white text-dark rounded-4 p-4 shadow">
                            <div class="text-info fs-2 mb-1"><i class="bi bi-people-fill"></i></div>
                            <h2 class="fw-bold mb-0"><?= number_format($stats['peserta']) ?></h2>
                            <div class="text-muted small">Pencari Kerja Terdaftar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Sesi Kedatangan (jika aktif) -->
<?php if (! empty($activeEvent['sesi_aktif']) && ! empty($sesiList)): ?>
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="badge badge-soft-primary mb-2">PENGATURAN KERUMUNAN</span>
                <h3 class="fw-bold mb-1">Jadwal & Kuota Sesi Kedatangan</h3>
                <p class="text-muted mb-0">Pilih sesi kedatangan saat mendaftar agar pelayanan check-in di lokasi berjalan tertib dan nyaman.</p>
            </div>
        </div>
        <div class="row g-3">
            <?php foreach ($sesiList as $s): ?>
                <?php
                    $sisa = max(0, (int)$s['kuota'] - (int)$s['terisi']);
                    $persen = $s['kuota'] > 0 ? min(100, round(($s['terisi'] / $s['kuota']) * 100)) : 100;
                    $penuh = ($sisa <= 0);
                ?>
                <div class="col-md-4">
                    <div class="card h-100 rounded-4 <?= $penuh ? 'border-danger bg-light' : 'card-hover' ?>">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0"><?= esc($s['nama']) ?></h5>
                                <?php if ($penuh): ?>
                                    <span class="badge bg-danger">PENUH</span>
                                <?php else: ?>
                                    <span class="badge badge-soft-success">Tersedia <?= number_format($sisa) ?> Kursi</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted small mb-3">
                                <i class="bi bi-calendar-event me-1"></i> <?= esc(date('d M Y', strtotime($s['tanggal']))) ?> &bull;
                                <i class="bi bi-clock me-1"></i> <?= esc(substr($s['jam_mulai'], 0, 5)) ?> - <?= esc(substr($s['jam_selesai'], 0, 5)) ?> WIB
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar <?= $penuh ? 'bg-danger' : 'bg-primary' ?>" style="width: <?= $persen ?>%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Terdaftar: <?= number_format($s['terisi']) ?></span>
                                <span>Kuota: <?= number_format($s['kuota']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Alur Mengikuti JMF -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Alur Melamar di Job Market Fair BLK Pasuruan</h3>
            <p class="text-muted">Tanpa berkas kertas (Paperless) — cukup dari smartphone Anda</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 rounded-4 card-hover text-center p-3">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;font-weight:700;">1</div>
                        <h6 class="fw-bold">Daftar & Lengkapi Profil</h6>
                        <p class="small text-muted mb-0">Buat akun, unggah pas foto (maks 1 MB), dan isi 1 link PDF gabungan berkas lamaran (Google Drive).</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 rounded-4 card-hover text-center p-3">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;font-weight:700;">2</div>
                        <h6 class="fw-bold">Pilih Sesi & Unduh Kartu QR</h6>
                        <p class="small text-muted mb-0">Daftar ke event aktif, pilih sesi kedatangan, lalu simpan atau cetak Kartu Peserta ber-QR Code.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 rounded-4 card-hover text-center p-3">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;font-weight:700;">3</div>
                        <h6 class="fw-bold">Check-in di Meja Registrasi</h6>
                        <p class="small text-muted mb-0">Datang ke BLK Pasuruan pada hari-H, tunjukkan QR kartu Anda kepada petugas untuk aktivasi kehadiran.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 rounded-4 card-hover text-center p-3">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;font-weight:700;">4</div>
                        <h6 class="fw-bold">Scan QR Booth & Pilih Jabatan</h6>
                        <p class="small text-muted mb-0">Scan poster QR di booth perusahaan dengan HP Anda, pilih 1 posisi yang diminati, dan pantau hasil seleksi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lowongan Terbaru -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Lowongan Kerja Tersedia</h3>
                <p class="text-muted mb-0">Beberapa posisi yang dibuka pada Job Market Fair tahun ini</p>
            </div>
            <a href="<?= base_url('lowongan') ?>" class="btn btn-outline-primary">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php foreach ($featuredLowongan as $l): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 rounded-4 card-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge badge-soft-primary">Booth <?= esc($l['no_booth']) ?></span>
                                <?php if (! empty($l['ramah_disabilitas'])): ?>
                                    <span class="badge badge-soft-success"><i class="bi bi-universal-access me-1"></i>Ramah Disabilitas</span>
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold mb-1"><?= esc($l['jabatan']) ?></h5>
                            <div class="text-primary fw-semibold small mb-3"><?= esc($l['nama_perusahaan']) ?></div>
                            <div class="small text-muted mb-3">
                                <div><i class="bi bi-mortarboard me-1"></i> Min. <?= esc($l['pendidikan_min']) ?> &bull; Gender: <?= esc($l['jenis_kelamin']) ?></div>
                                <div><i class="bi bi-people me-1"></i> Kuota: <strong><?= (int)$l['kuota'] ?> orang</strong></div>
                                <div><i class="bi bi-geo-alt me-1"></i> <?= esc($l['lokasi_kerja']) ?></div>
                            </div>
                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold text-success">
                                    <?php if ($l['gaji_min']): ?>
                                        Rp <?= number_format($l['gaji_min'], 0, ',', '.') ?>+
                                    <?php else: ?>
                                        Gaji Kompetitif
                                    <?php endif; ?>
                                </span>
                                <a href="<?= base_url('lowongan/' . $l['id']) ?>" class="btn btn-sm btn-primary px-3">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
