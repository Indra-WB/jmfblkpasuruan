<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card stat-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <span class="badge bg-primary px-3 py-2 mb-2">BOOTH <?= esc($ep['no_booth']) ?></span>
                        <h4 class="fw-bold mb-1"><?= esc($ep['nama_perusahaan']) ?></h4>
                        <div class="text-muted small"><?= esc($ep['bidang_usaha']) ?> &bull; <?= esc($ep['alamat']) ?>
                        </div>
                    </div>
                </div>

                <?php if (!$kepesertaan): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle-fill me-1"></i> <strong>Belum Terdaftar di Event!</strong> Anda belum
                        mendaftar pada <?= esc($ep['nama_event']) ?>.
                        <div class="mt-2"><a href="<?= base_url('pencaker') ?>" class="btn btn-sm btn-danger">Daftar Event
                                Sekarang</a></div>
                    </div>
                <?php elseif (empty($kepesertaan['checkin_at'])): ?>
                    <div class="alert alert-warning border-warning">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Wajib Check-in Terlebih
                            Dahulu!</strong><br>
                        Status Anda saat ini belum Check-in. Silakan menuju <strong>Meja Registrasi Depan</strong> dan
                        tunjukkan Kartu QR Peserta Anda untuk dipindai oleh petugas sebelum melamar di booth.
                    </div>
                <?php elseif (empty($pencaker['profil_lengkap']) || empty($pencaker['link_berkas'])): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Profil atau Link Berkas PDF Belum
                            Lengkap!</strong>
                        <div class="mt-2"><a href="<?= base_url('pencaker/profil') ?>"
                                class="btn btn-sm btn-warning">Lengkapi Profil & Link PDF</a></div>
                    </div>
                <?php elseif ($existingLamaran): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-check2-circle me-1"></i> <strong>Anda Sudah Melamar di Perusahaan Ini!</strong><br>
                        Sesuai aturan Job Market Fair (maksimal <strong>1 lowongan per perusahaan</strong>), Anda telah
                        melamar posisi <strong><?= esc($existingLamaran['jabatan']) ?></strong> pada
                        <?= esc(date('d M Y H:i', strtotime($existingLamaran['created_at']))) ?> dengan status saat ini:
                        <span class="badge bg-primary"><?= esc($existingLamaran['status']) ?></span>.
                    </div>
                <?php else: ?>
                    <div class="alert alert-success small mb-3">
                        <i class="bi bi-check-circle-fill me-1"></i> Status kehadiran Anda <strong>SUDAH CHECK-IN</strong>.
                        Silakan pilih <strong>1 lowongan jabatan</strong> yang ingin Anda lamar di
                        <strong><?= esc($ep['nama_perusahaan']) ?></strong>. Link berkas gabungan PDF Anda akan otomatis
                        dilampirkan ke HRD.
                    </div>

                    <form method="post" action="<?= base_url('lamar/' . $ep['qr_token']) ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <?php foreach ($lowonganList as $l): ?>
                                <label class="card mb-2 border rounded-3 p-3 d-block cursor-pointer">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="lowongan_id"
                                                value="<?= $l['id'] ?>" required>
                                            <span class="fw-bold fs-6"><?= esc($l['jabatan']) ?></span>
                                        </div>
                                        <span class="badge badge-soft-primary">Kuota: <?= (int) $l['kuota'] ?></span>
                                    </div>
                                    <div class="small text-muted ms-4 mt-1">
                                        Min. Pendidikan: <strong><?= esc($l['pendidikan_min']) ?></strong> &bull;
                                        Gender: <?= $l['jenis_kelamin'] === 'L' ? 'Laki-Laki' : ($l['jenis_kelamin'] === 'P' ? 'Perempuan' : 'Laki-Laki / Perempuan') ?> &bull;
                                        Kuota: <strong><?= (int) $l['kuota'] ?> Orang</strong> &bull;
                                        Lokasi: <?= esc($l['lokasi_kerja']) ?>
                                    </div>
                                    <div class="small text-secondary ms-4 mt-1"><?= esc($l['kualifikasi']) ?></div>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold"
                            onclick="return confirm('Kirim lamaran ke jabatan yang dipilih? Ingat: Anda hanya bisa memilih 1 lowongan di perusahaan ini.')">
                            <i class="bi bi-send-check-fill me-1"></i> Kirim Lamaran Sekarang
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>