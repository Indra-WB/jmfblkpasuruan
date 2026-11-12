<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Job Market Fair') ?> — <?= esc($pengaturan['nama_instansi'] ?? 'UPT BLK Pasuruan') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; display: flex; flex-direction: column; min-height: 100vh; }
        .navbar-blk { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); }
        .hero-blk { background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #fff; }
        .card-hover { transition: transform .2s ease, box-shadow .2s ease; border: 1px solid #e2e8f0; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.12); }
        .badge-soft-primary { background-color: #dbeafe; color: #1d4ed8; }
        .badge-soft-success { background-color: #dcfce7; color: #15803d; }
        .badge-soft-warning { background-color: #fef3c7; color: #b45309; }
        footer { margin-top: auto; background: #0f172a; color: #cbd5e1; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-blk shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= base_url('/') ?>">
            <img src="<?= base_url('logo-white.png') ?>" alt="Logo JMF BLK Pasuruan" style="height: 36px; width: auto;">
            <div>
                <div class="lh-1">JMF BLK PASURUAN</div>
                <small class="fw-normal text-white-50" style="font-size: 0.7rem;">Disnakertrans Prov. Jawa Timur</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPublic">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === '' ? 'active fw-semibold' : '' ?>" href="<?= base_url('/') ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with(uri_string(), 'lowongan') ? 'active fw-semibold' : '' ?>" href="<?= base_url('lowongan') ?>">Daftar Lowongan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with(uri_string(), 'perusahaan-peserta') ? 'active fw-semibold' : '' ?>" href="<?= base_url('perusahaan-peserta') ?>">Perusahaan Peserta</a>
                </li>
            </ul>
            <div class="d-flex gap-2">
                <?php if (session()->get('logged_in')): ?>
                    <?php
                        $role = session()->get('role');
                        $dashUrl = match($role) {
                            'admin'      => base_url('admin'),
                            'perusahaan' => base_url('perusahaan'),
                            'petugas'    => base_url('petugas'),
                            'pimpinan'   => base_url('pimpinan'),
                            default      => base_url('pencaker'),
                        };
                    ?>
                    <a href="<?= $dashUrl ?>" class="btn btn-warning fw-semibold btn-sm px-3">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard (<?= esc(ucfirst($role)) ?>)
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">Keluar</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                    <a href="<?= base_url('daftar') ?>" class="btn btn-warning fw-semibold btn-sm px-3">
                        <i class="bi bi-person-plus-fill me-1"></i> Daftar Pencari Kerja
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php if (session()->getFlashdata('success')): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<main>
    <?= $this->renderSection('content') ?>
</main>

<footer class="py-4 mt-5">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="<?= base_url('logo-white.png') ?>" alt="Logo JMF BLK Pasuruan" style="height: 30px; width: auto;">
                    <h6 class="text-white fw-bold mb-0"><?= esc($pengaturan['nama_instansi'] ?? 'UPT Balai Latihan Kerja Pasuruan') ?></h6>
                </div>
                <p class="small mb-1"><?= esc($pengaturan['sub_instansi'] ?? '') ?></p>
                <p class="small mb-0 text-secondary">
                    <i class="bi bi-geo-alt me-1"></i><?= esc($pengaturan['alamat_instansi'] ?? '') ?> |
                    <i class="bi bi-envelope me-1"></i><?= esc($pengaturan['email_instansi'] ?? 'jmf@uptblkpasuruan.com') ?>
                </p>
            </div>
            <div class="col-md-5 text-md-end small text-secondary">
                &copy; <?= date('Y') ?> Sistem Informasi Job Market Fair UPT BLK Pasuruan.
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
