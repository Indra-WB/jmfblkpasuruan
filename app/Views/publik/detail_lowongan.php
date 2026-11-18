<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('lowongan') ?>">Lowongan</a></li>
            <li class="breadcrumb-item active"><?= esc($low['jabatan']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-primary px-3 py-2">Nomor Booth: <?= esc($low['no_booth']) ?></span>
                        <span class="badge badge-soft-primary px-3 py-2">Pendidikan Min: <?= esc($low['pendidikan_min']) ?></span>
                        <?php if (! empty($low['ramah_disabilitas'])): ?>
                            <span class="badge badge-soft-success px-3 py-2"><i class="bi bi-universal-access me-1"></i> Terbuka untuk Disabilitas</span>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <?php if (! empty($low['logo'])): ?>
                            <img src="<?= base_url('media/logo/' . $low['logo']) ?>" alt="Logo <?= esc($low['nama_perusahaan']) ?>" class="rounded border p-1 bg-white flex-shrink-0" style="width: 64px; height: 64px; object-fit: contain;">
                        <?php endif; ?>
                        <div>
                            <h2 class="fw-bold mb-1"><?= esc($low['jabatan']) ?></h2>
                            <h5 class="text-primary fw-semibold mb-0"><?= esc($low['nama_perusahaan']) ?></h5>
                        </div>
                    </div>

                    <div class="row g-3 bg-light rounded-3 p-3 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <small class="text-muted d-block">Kuota Kebutuhan</small>
                            <strong><?= (int)$low['kuota'] ?> Orang</strong>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <small class="text-muted d-block">Jenis Kelamin</small>
                            <strong><?= $low['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($low['jenis_kelamin'] === 'P' ? 'Perempuan' : 'Laki-laki / Perempuan') ?></strong>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <small class="text-muted d-block">Batas Usia</small>
                            <strong><?= $low['usia_max'] ? 'Maks. ' . esc($low['usia_max']) . ' Tahun' : 'Tidak dibatasi' ?></strong>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <small class="text-muted d-block">Penempatan</small>
                            <strong><?= esc($low['lokasi_kerja']) ?></strong>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-2">Deskripsi Pekerjaan</h5>
                    <p class="mb-4" style="white-space: pre-line;"><?= esc($low['deskripsi']) ?></p>

                    <h5 class="fw-bold mb-2">Kualifikasi & Persyaratan</h5>
                    <p class="mb-0" style="white-space: pre-line;"><?= esc($low['kualifikasi']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card rounded-4 border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-qr-code-scan text-primary me-1"></i> Cara Melamar Lowongan Ini</h6>
                    <p class="small text-muted mb-3">
                        Sesuai prosedur Job Market Fair BLK Pasuruan, pengiriman lamaran dilakukan <strong>langsung di lokasi acara pada hari-H</strong> dengan memindai QR Code yang terpasang di <strong>Booth <?= esc($low['no_booth']) ?> (<?= esc($low['nama_perusahaan']) ?>)</strong> setelah Anda melakukan Check-in di Meja Registrasi.
                    </p>
                    <div class="alert alert-warning small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i> Setiap pencari kerja dapat melamar <strong>maksimal 1 posisi jabatan per perusahaan</strong>.
                    </div>
                    <?php if (session()->get('role') === 'pencaker'): ?>
                        <a href="<?= base_url('pencaker/scan-booth') ?>" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-camera me-1"></i> Buka Pemindai QR Booth
                        </a>
                    <?php elseif (! session()->get('logged_in')): ?>
                        <a href="<?= base_url('daftar') ?>" class="btn btn-warning w-100 fw-bold mb-2">Daftar Sebagai Peserta</a>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-primary w-100">Sudah Punya Akun? Masuk</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">Tentang Perusahaan</h6>
                    <div class="fw-semibold"><?= esc($low['nama_perusahaan']) ?></div>
                    <div class="small text-muted mb-2"><?= esc($low['bidang_usaha']) ?></div>
                    <p class="small text-secondary mb-2"><?= esc($low['deskripsi_perusahaan']) ?></p>
                    <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i> <?= esc($low['alamat']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
