<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?php if ($seleksiClosed): ?>
    <div class="alert alert-warning"><i class="bi bi-lock-fill me-1"></i> Masa seleksi event ini telah berakhir pada <?= esc($activeEvent['batas_seleksi']) ?>. Perubahan status pelamar telah dikunci.</div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="small text-muted">Nomor Booth</div>
            <h3 class="fw-bold text-primary mb-0"><?= esc($ep['no_booth'] ?? '-') ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="small text-muted">Lowongan & Kuota</div>
            <h3 class="fw-bold mb-0"><?= $stats['lowongan'] ?> <small class="fs-6 text-muted fw-normal">(<?= $stats['kuota'] ?> org)</small></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="small text-muted">Total Lamaran Masuk</div>
            <h3 class="fw-bold text-info mb-0"><?= $stats['pelamar'] ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="small text-muted">Pelamar Diterima Kerja</div>
            <h3 class="fw-bold text-success mb-0"><?= $stats['diterima'] ?></h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card stat-card p-4">
            <h5 class="fw-bold mb-2"><?= esc($perusahaan['nama']) ?></h5>
            <p class="small text-muted mb-3"><?= esc($perusahaan['bidang_usaha']) ?> &bull; <?= esc($perusahaan['alamat']) ?></p>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('perusahaan/lowongan') ?>" class="btn btn-primary"><i class="bi bi-briefcase me-1"></i> Kelola Lowongan</a>
                <a href="<?= base_url('perusahaan/pelamar') ?>" class="btn btn-success"><i class="bi bi-people me-1"></i> Seleksi Pelamar (<?= $stats['pelamar'] ?>)</a>
                <a href="<?= base_url('perusahaan/pelamar/export-excel') ?>" class="btn btn-outline-success"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel Pelamar</a>
                <?php if ($ep): ?>
                    <a href="<?= base_url('perusahaan/poster-qr') ?>" target="_blank" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Cetak Poster QR Booth</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <?php if ($ep && $qrSvg): ?>
            <div class="card stat-card p-4 text-center">
                <span class="badge bg-primary align-self-center mb-2">QR CODE BOOTH <?= esc($ep['no_booth']) ?></span>
                <div class="mx-auto my-2 p-2 border rounded-3" style="max-width: 200px;">
                    <img src="<?= $qrSvg ?>" alt="QR Booth" class="img-fluid">
                </div>
                <code class="small d-block mb-2"><?= esc($ep['qr_token']) ?></code>
                <p class="small text-muted mb-0">Pelamar memindai QR Code ini di booth Anda untuk memilih jabatan dan mengirim lamaran.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
