<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-2 g-md-3 mb-4">
    <div class="col-4 col-lg-2">
        <div class="card stat-card p-2 p-md-3 h-100 justify-content-between">
            <div class="small text-muted">Booth Perusahaan</div>
            <h3 class="fw-bold text-primary mb-0"><?= number_format($stats['perusahaan']) ?></h3>
        </div>
    </div>
    <div class="col-4 col-lg-2">
        <div class="card stat-card p-2 p-md-3 h-100 justify-content-between">
            <div class="small text-muted">Akun Pencaker</div>
            <h3 class="fw-bold mb-0"><?= number_format($stats['pencaker']) ?></h3>
        </div>
    </div>
    <div class="col-4 col-lg-2">
        <div class="card stat-card p-2 p-md-3 h-100 justify-content-between">
            <div class="small text-muted">Peserta Event</div>
            <h3 class="fw-bold text-info mb-0"><?= number_format($stats['peserta']) ?></h3>
        </div>
    </div>
    <div class="col-4 col-lg-2">
        <div class="card stat-card p-2 p-md-3 h-100 justify-content-between">
            <div class="small text-muted">Hadir Check-in</div>
            <h3 class="fw-bold text-success mb-0"><?= number_format($stats['hadir']) ?></h3>
        </div>
    </div>
    <div class="col-4 col-lg-2">
        <div class="card stat-card p-2 p-md-3 h-100 justify-content-between">
            <div class="small text-muted">Lamaran Masuk</div>
            <h3 class="fw-bold text-warning mb-0"><?= number_format($stats['lamaran']) ?></h3>
        </div>
    </div>
    <div class="col-4 col-lg-2">
        <div class="card stat-card p-2 p-md-3 h-100 justify-content-between bg-success text-white">
            <div class="small text-white-50">Diterima Kerja</div>
            <h3 class="fw-bold mb-0"><?= number_format($stats['diterima']) ?></h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card p-4">
            <h5 class="fw-bold mb-2">Pusat Kendali Panitia Job Market Fair UPT BLK Pasuruan</h5>
            <p class="small text-muted mb-3">Gunakan pintasan di bawah ini untuk mengelola seluruh kegiatan mulai
                persiapan, pelaksanaan hari-H, hingga evaluasi penempatan:</p>
            <div class="row g-2">
                <div class="col-sm-6 col-md-4">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-outline-primary w-100 text-start p-3">
                        <i class="bi bi-calendar-event fs-4 d-block mb-1"></i>
                        <strong>Kelola Event</strong>
                        <small class="d-block text-muted">Buka/tutup lamaran & batas seleksi</small>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="<?= base_url('admin/sesi') ?>" class="btn btn-outline-primary w-100 text-start p-3">
                        <i class="bi bi-clock-history fs-4 d-block mb-1"></i>
                        <strong>Sesi Kedatangan</strong>
                        <small class="d-block text-muted">Atur kuota per sesi & bagi peserta</small>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="<?= base_url('admin/perusahaan') ?>" class="btn btn-outline-primary w-100 text-start p-3">
                        <i class="bi bi-buildings fs-4 d-block mb-1"></i>
                        <strong>Perusahaan & Booth</strong>
                        <small class="d-block text-muted">Buat akun HRD & cetak poster QR</small>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="<?= base_url('petugas') ?>" class="btn btn-outline-success w-100 text-start p-3">
                        <i class="bi bi-qr-code-scan fs-4 d-block mb-1"></i>
                        <strong>Scanner Check-in</strong>
                        <small class="d-block text-muted">Meja registrasi kehadiran peserta</small>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="<?= base_url('pimpinan') ?>" class="btn btn-outline-info w-100 text-start p-3">
                        <i class="bi bi-broadcast fs-4 d-block mb-1"></i>
                        <strong>Monitor Real-Time</strong>
                        <small class="d-block text-muted">Layar grafik statistik pada hari-H</small>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="<?= base_url('pimpinan/laporan') ?>" class="btn btn-outline-danger w-100 text-start p-3">
                        <i class="bi bi-file-earmark-pdf fs-4 d-block mb-1"></i>
                        <strong>Laporan & PDF</strong>
                        <small class="d-block text-muted">Cetak laporan berkop BLK & Excel</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card stat-card p-4">
            <h6 class="fw-bold mb-2"><i class="bi bi-envelope-paper text-primary me-1"></i> Status Antrean Email
                Notifikasi</h6>
            <p class="small text-muted mb-3">Terdapat <strong><?= $stats['email_antre'] ?> email</strong> dalam antrean
                kirim.</p>
            <form method="post" action="<?= base_url('admin/email/proses') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary btn-sm w-100 mb-2"><i class="bi bi-send me-1"></i> Proses
                    Kirim Antrean Sekarang</button>
            </form>
            <a href="<?= base_url('admin/pengaturan') ?>" class="btn btn-outline-secondary btn-sm w-100">Konfigurasi
                SMTP Email (`jmf@uptblkpasuruan.com`)</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>