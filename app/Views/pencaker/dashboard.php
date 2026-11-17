<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?php if (empty($pencaker['profil_lengkap']) || empty($pencaker['link_berkas'])): ?>
    <div
        class="alert alert-warning border-warning shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong>Profil & Link Berkas Belum Lengkap!</strong> Silakan lengkapi biodata, pas foto, dan link PDF gabungan
            berkas lamaran agar dapat mendaftar event & melamar di booth.
        </div>
        <a href="<?= base_url('pencaker/profil') ?>" class="btn btn-warning btn-sm fw-bold">Lengkapi Sekarang</a>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card stat-card mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1"><?= esc($activeEvent['nama'] ?? 'Job Market Fair UPT BLK Pasuruan') ?></h5>
                <p class="small text-muted mb-3">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= $activeEvent ? esc(date('d M Y', strtotime($activeEvent['tanggal']))) : '-' ?> &bull;
                    <i class="bi bi-geo-alt me-1"></i> <?= esc($activeEvent['lokasi'] ?? '-') ?>
                </p>

                <?php if (!$kepesertaan): ?>
                    <hr>
                    <h6 class="fw-bold mb-2">Pendaftaran Kepesertaan Event</h6>
                    <?php if (empty($pencaker['profil_lengkap']) || empty($pencaker['link_berkas'])): ?>
                        <p class="small text-danger mb-2">Anda harus melengkapi profil dan link berkas PDF terlebih dahulu
                            sebelum mendaftar event.</p>
                        <a href="<?= base_url('pencaker/profil') ?>" class="btn btn-primary btn-sm">Ke Halaman Profil</a>
                    <?php else: ?>
                        <form method="post" action="<?= base_url('pencaker/event/daftar') ?>">
                            <?= csrf_field() ?>
                            <?php if (!empty($activeEvent['sesi_aktif'])): ?>
                                <label class="form-label fw-semibold small">Pilih Sesi Kedatangan <span
                                        class="text-danger">*</span></label>
                                <div class="row g-2 mb-3">
                                    <?php $allFull = true; ?>
                                    <?php foreach ($sesiList as $s): ?>
                                        <?php
                                        $sisa = max(0, (int) $s['kuota'] - (int) $s['terisi']);
                                        $penuh = ($sisa <= 0);
                                        if (!$penuh)
                                            $allFull = false;
                                        ?>
                                        <div class="col-md-6">
                                            <label
                                                class="border rounded-3 p-3 d-block <?= $penuh ? 'bg-light text-muted' : 'cursor-pointer' ?>">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="sesi_id" value="<?= $s['id'] ?>"
                                                        <?= $penuh ? 'disabled' : 'required' ?>>
                                                    <span class="fw-bold"><?= esc($s['nama']) ?></span>
                                                </div>
                                                <div class="small mt-1">
                                                    <?= esc(substr($s['jam_mulai'], 0, 5)) ?> -
                                                    <?= esc(substr($s['jam_selesai'], 0, 5)) ?> WIB
                                                </div>
                                                <div class="small fw-semibold <?= $penuh ? 'text-danger' : 'text-success' ?>">
                                                    <?= $penuh ? 'Kuota Penuh' : 'Sisa Kuota: ' . number_format($sisa) ?>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($allFull): ?>
                                    <div class="alert alert-danger small">Kuota pendaftaran semua sesi telah penuh.</div>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-primary fw-semibold">
                                        <i class="bi bi-qr-code me-1"></i> Daftar Event & Dapatkan Kartu QR
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="bi bi-qr-code me-1"></i> Daftar Event & Dapatkan Kartu QR
                                </button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="row g-3 bg-light rounded-3 p-3 mb-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nomor Registrasi</small>
                            <span class="fw-bold fs-5 text-primary"><?= esc($kepesertaan['no_registrasi']) ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Status Kehadiran (Check-in)</small>
                            <?php if ($kepesertaan['checkin_at']): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> SUDAH CHECK-IN
                                    (<?= esc(date('H:i', strtotime($kepesertaan['checkin_at']))) ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> BELUM
                                    CHECK-IN DI LOKASI</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($activeEvent['sesi_aktif']) && !empty($kepesertaan['nama_sesi'])): ?>
                            <div class="col-12 border-top pt-2">
                                <small class="text-muted d-block">Jadwal Sesi Kedatangan Anda</small>
                                <strong><i class="bi bi-clock me-1 text-primary"></i> <?= esc($kepesertaan['nama_sesi']) ?>
                                    (<?= esc(substr($kepesertaan['jam_mulai'], 0, 5)) ?> -
                                    <?= esc(substr($kepesertaan['jam_selesai'], 0, 5)) ?> WIB)</strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('pencaker/kartu-qr') ?>" target="_blank" class="btn btn-danger fw-semibold">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Kartu Peserta (PDF)
                        </a>
                        <a href="<?= base_url('pencaker/scan-booth') ?>" class="btn btn-primary fw-semibold">
                            <i class="bi bi-qr-code-scan me-1"></i> Scan QR Booth (Lamar Kerja)
                        </a>
                    </div>

                    <!-- Opsi Pindah Sesi selama belum check-in -->
                    <?php if (!empty($activeEvent['sesi_aktif']) && empty($kepesertaan['checkin_at']) && !empty($sesiList)): ?>
                        <hr class="my-3">
                        <form method="post" action="<?= base_url('pencaker/event/pindah-sesi') ?>"
                            class="row g-2 align-items-end">
                            <?= csrf_field() ?>
                            <div class="col-sm-8">
                                <label class="form-label small fw-semibold mb-1">Ingin Pindah Sesi Kedatangan?</label>
                                <select name="sesi_id" class="form-select form-select-sm" required>
                                    <?php foreach ($sesiList as $s): ?>
                                        <?php $sisa = max(0, (int) $s['kuota'] - (int) $s['terisi']); ?>
                                        <option value="<?= $s['id'] ?>" <?= $s['id'] == $kepesertaan['sesi_id'] ? 'selected' : '' ?>
                                            <?= $sisa <= 0 ? 'disabled' : '' ?>>
                                            <?= esc($s['nama']) ?>
                                            (<?= substr($s['jam_mulai'], 0, 5) ?>-<?= substr($s['jam_selesai'], 0, 5) ?>) — Sisa:
                                            <?= $sisa ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Simpan Sesi</button>
                            </div>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-sm-6">
                <div class="card stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-muted">Lamaran Dikirim</div>
                            <h3 class="fw-bold mb-0"><?= $jmlLamaran ?></h3>
                        </div>
                        <a href="<?= base_url('pencaker/lamaran') ?>" class="btn btn-sm btn-outline-primary">Lihat
                            Status</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-muted">Berkas Lamaran</div>
                            <div
                                class="fw-bold <?= !empty($pencaker['link_berkas']) ? 'text-success' : 'text-danger' ?>">
                                <?= !empty($pencaker['link_berkas']) ? 'Tersimpan' : 'Belum Diisi' ?>
                            </div>
                        </div>
                        <?php if (!empty($pencaker['link_berkas'])): ?>
                            <a href="<?= esc($pencaker['link_berkas']) ?>" target="_blank" rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-success">Buka Link</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <?php if ($kepesertaan && $qrSvg): ?>
            <div class="card stat-card text-center p-4">
                <span class="badge bg-dark align-self-center mb-2">KARTU QR CHECK-IN MEJA REGISTRASI</span>
                <div class="mx-auto my-2 p-2 bg-white border rounded-3" style="max-width: 220px;">
                    <img src="<?= $qrSvg ?>" alt="QR Code Peserta" class="img-fluid">
                </div>
                <div class="fw-bold fs-5"><?= esc($pencaker['nama']) ?></div>
                <div class="text-muted small mb-1">NIK: <?= esc($pencaker['nik']) ?></div>
                <code class="d-block mb-3"><?= esc($kepesertaan['qr_token']) ?></code>
                <div class="alert alert-info small mb-0 text-start">
                    <i class="bi bi-info-circle-fill me-1"></i> Tunjukkan QR Code ini kepada <strong>Petugas Meja
                        Registrasi</strong> saat tiba di lokasi BLK Pasuruan agar status Anda menjadi
                    <strong>Check-in</strong> dan bisa melamar di booth perusahaan. Pastikan Anda sudah melengkapi Biodata,
                    Pas Foto & Link Berkas Lamaran untuk melamar kerja.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>