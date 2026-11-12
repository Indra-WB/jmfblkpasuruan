<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Panel') ?> — JMF BLK Pasuruan</title>
    <link rel="icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .sidebar { width: 260px; min-height: 100vh; background: #0f172a; color: #e2e8f0; flex-shrink: 0; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: .5rem; padding: .6rem .9rem; margin-bottom: .2rem; font-size: .92rem; display: flex; align-items: center; gap: .65rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #1e3a8a; color: #fff; font-weight: 600; }
        .sidebar-heading { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: #64748b; padding: .75rem .9rem .35rem; font-weight: 700; }
        .stat-card { border: none; border-radius: .75rem; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
        @media (max-width: 991.98px) {
            .sidebar { display: none; }
        }
    </style>
    <?= $this->renderSection('head') ?>
</head>
<body>
<?php
    $role = session()->get('role');
    $uri  = uri_string();
?>
<div class="d-flex">
    <!-- Sidebar Desktop -->
    <aside class="sidebar p-3 d-none d-lg-flex flex-column">
        <a href="<?= base_url('/') ?>" class="text-decoration-none text-white d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-secondary">
            <img src="<?= base_url('logo-white.png') ?>" alt="Logo JMF BLK Pasuruan" style="height: 36px; width: auto;">
            <div>
                <div class="fw-bold lh-1">JMF BLK PASURUAN</div>
                <small class="text-white-50" style="font-size: .72rem;">Panel <?= esc(ucfirst($role)) ?></small>
            </div>
        </a>

        <ul class="nav flex-column mb-auto">
            <?php if ($role === 'admin'): ?>
                <div class="sidebar-heading">Menu Utama</div>
                <li class="nav-item"><a class="nav-link <?= $uri === 'admin' ? 'active' : '' ?>" href="<?= base_url('admin') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'pimpinan') ? 'active' : '' ?>" href="<?= base_url('pimpinan') ?>"><i class="bi bi-broadcast"></i> Monitor Real-Time</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/events') ? 'active' : '' ?>" href="<?= base_url('admin/events') ?>"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/sesi') ? 'active' : '' ?>" href="<?= base_url('admin/sesi') ?>"><i class="bi bi-clock-history"></i> Sesi Kedatangan</a></li>
                <div class="sidebar-heading">Peserta & Lowongan</div>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/perusahaan') ? 'active' : '' ?>" href="<?= base_url('admin/perusahaan') ?>"><i class="bi bi-buildings"></i> Perusahaan & Booth</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/lowongan') ? 'active' : '' ?>" href="<?= base_url('admin/lowongan') ?>"><i class="bi bi-journal-text"></i> Semua Lowongan</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/pencaker') ? 'active' : '' ?>" href="<?= base_url('admin/pencaker') ?>"><i class="bi bi-people"></i> Data Pencari Kerja</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'petugas') ? 'active' : '' ?>" href="<?= base_url('petugas') ?>"><i class="bi bi-qr-code-scan"></i> Scanner Check-in</a></li>
                <div class="sidebar-heading">Evaluasi & Sistem</div>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/laporan') || str_starts_with($uri, 'pimpinan/laporan') ? 'active' : '' ?>" href="<?= base_url('pimpinan/laporan') ?>"><i class="bi bi-file-earmark-bar-graph"></i> Laporan & Cetak PDF</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/survei') ? 'active' : '' ?>" href="<?= base_url('admin/survei') ?>"><i class="bi bi-ui-checks"></i> Kuesioner Survei</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/pengguna') ? 'active' : '' ?>" href="<?= base_url('admin/pengguna') ?>"><i class="bi bi-person-badge"></i> Akun Petugas/Pimpinan</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'admin/pengaturan') ? 'active' : '' ?>" href="<?= base_url('admin/pengaturan') ?>"><i class="bi bi-gear"></i> Pengaturan & SMTP</a></li>
            <?php elseif ($role === 'perusahaan'): ?>
                <div class="sidebar-heading">Menu Perusahaan</div>
                <li class="nav-item"><a class="nav-link <?= $uri === 'perusahaan' ? 'active' : '' ?>" href="<?= base_url('perusahaan') ?>"><i class="bi bi-speedometer2"></i> Ringkasan & Booth</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'perusahaan/profil') ? 'active' : '' ?>" href="<?= base_url('perusahaan/profil') ?>"><i class="bi bi-building"></i> Profil Perusahaan</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'perusahaan/lowongan') ? 'active' : '' ?>" href="<?= base_url('perusahaan/lowongan') ?>"><i class="bi bi-briefcase"></i> Kelola Lowongan</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'perusahaan/pelamar') ? 'active' : '' ?>" href="<?= base_url('perusahaan/pelamar') ?>"><i class="bi bi-people-fill"></i> Seleksi Pelamar</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'perusahaan/penempatan') ? 'active' : '' ?>" href="<?= base_url('perusahaan/penempatan') ?>"><i class="bi bi-award"></i> Rekap Penempatan</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'perusahaan/poster-qr') ? 'active' : '' ?>" href="<?= base_url('perusahaan/poster-qr') ?>" target="_blank"><i class="bi bi-qr-code"></i> Cetak Poster QR Booth</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'perusahaan/survei') ? 'active' : '' ?>" href="<?= base_url('perusahaan/survei') ?>"><i class="bi bi-star"></i> Survei Kepuasan</a></li>
            <?php elseif ($role === 'pencaker'): ?>
                <div class="sidebar-heading">Menu Pencari Kerja</div>
                <li class="nav-item"><a class="nav-link <?= $uri === 'pencaker' ? 'active' : '' ?>" href="<?= base_url('pencaker') ?>"><i class="bi bi-house-door"></i> Dashboard & Kartu QR</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'pencaker/profil') ? 'active' : '' ?>" href="<?= base_url('pencaker/profil') ?>"><i class="bi bi-person-vcard"></i> Profil, Foto & Berkas</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'pencaker/scan-booth') ? 'active' : '' ?>" href="<?= base_url('pencaker/scan-booth') ?>"><i class="bi bi-qr-code-scan"></i> Scan QR Booth (Lamar)</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'pencaker/lamaran') ? 'active' : '' ?>" href="<?= base_url('pencaker/lamaran') ?>"><i class="bi bi-send-check"></i> Riwayat & Status Lamaran</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'pencaker/survei') ? 'active' : '' ?>" href="<?= base_url('pencaker/survei') ?>"><i class="bi bi-chat-heart"></i> Isi Survei Kepuasan</a></li>
            <?php elseif ($role === 'petugas'): ?>
                <div class="sidebar-heading">Meja Registrasi</div>
                <li class="nav-item"><a class="nav-link <?= $uri === 'petugas' ? 'active' : '' ?>" href="<?= base_url('petugas') ?>"><i class="bi bi-qr-code-scan"></i> Scan Check-in Peserta</a></li>
            <?php elseif ($role === 'pimpinan'): ?>
                <div class="sidebar-heading">Menu Pimpinan / Disnaker</div>
                <li class="nav-item"><a class="nav-link <?= $uri === 'pimpinan' ? 'active' : '' ?>" href="<?= base_url('pimpinan') ?>"><i class="bi bi-graph-up-arrow"></i> Dashboard Real-Time</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with($uri, 'pimpinan/laporan') ? 'active' : '' ?>" href="<?= base_url('pimpinan/laporan') ?>"><i class="bi bi-file-earmark-pdf"></i> Laporan & Export</a></li>
            <?php endif; ?>
        </ul>

        <div class="mt-4 pt-3 border-top border-secondary small">
            <div class="fw-semibold text-white text-truncate"><?= esc(session()->get('nama')) ?></div>
            <div class="text-white-50 text-truncate mb-2"><?= esc(session()->get('email')) ?></div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-light w-50"><i class="bi bi-globe"></i> Web</a>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-danger w-50"><i class="bi bi-box-arrow-right"></i> Keluar</a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100" style="min-width: 0;">
        <!-- Topbar -->
        <header class="bg-white border-bottom px-3 px-lg-4 py-3 d-flex justify-content-between align-items-center sticky-top shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-secondary d-lg-none"><i class="bi bi-house"></i></a>
                <h5 class="mb-0 fw-bold text-dark"><?= esc($title ?? 'Dashboard') ?></h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if (! empty($activeEvent)): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-none d-md-inline-block px-3 py-2">
                        <i class="bi bi-calendar-check me-1"></i> <?= esc($activeEvent['nama']) ?>
                    </span>
                <?php endif; ?>
                <!-- Mobile quick navigation -->
                <div class="dropdown d-lg-none">
                    <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">Menu</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if ($role === 'pencaker'): ?>
                            <li><a class="dropdown-item" href="<?= base_url('pencaker') ?>">Dashboard & Kartu QR</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pencaker/profil') ?>">Profil, Foto & Berkas</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pencaker/scan-booth') ?>">Scan QR Booth (Lamar)</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pencaker/lamaran') ?>">Status Lamaran</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pencaker/survei') ?>">Survei</a></li>
                        <?php elseif ($role === 'perusahaan'): ?>
                            <li><a class="dropdown-item" href="<?= base_url('perusahaan') ?>">Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('perusahaan/profil') ?>">Profil</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('perusahaan/lowongan') ?>">Lowongan</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('perusahaan/pelamar') ?>">Seleksi Pelamar</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('perusahaan/penempatan') ?>">Rekap Penempatan</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('perusahaan/survei') ?>">Survei</a></li>
                        <?php elseif ($role === 'admin'): ?>
                            <li><a class="dropdown-item" href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/events') ?>">Event</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/sesi') ?>">Sesi Kedatangan</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/perusahaan') ?>">Perusahaan</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/pencaker') ?>">Pencari Kerja</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('petugas') ?>">Scanner Check-in</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pimpinan/laporan') ?>">Laporan</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="p-3 p-lg-4 flex-grow-1">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function() {
        $('.datatable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { previous: "Sebelumnya", next: "Berikutnya" },
                zeroRecords: "Tidak ada data yang cocok"
            }
        });
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
