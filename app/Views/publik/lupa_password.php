<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('logo.png') ?>" alt="Logo JMF" class="mb-2"
                            style="height: 56px; width: auto;">
                        <h4 class="fw-bold mb-1">Lupa Kata Sandi</h4>
                        <p class="small text-muted mb-0">
                            <?php if (! $detectedRole): ?>
                                Masukkan alamat email akun Anda untuk verifikasi
                            <?php elseif ($detectedRole === 'pencaker'): ?>
                                Verifikasi Data Pencari Kerja
                            <?php else: ?>
                                Verifikasi Data Perusahaan Peserta
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if (! $detectedRole): ?>
                        <!-- Langkah 1: Deteksi Otomatis dari Email -->
                        <form method="post" action="<?= base_url('lupa-password') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="step" value="cek_email">
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Alamat Email Terdaftar</label>
                                <input type="email" name="email" value="<?= esc($email) ?>" class="form-control"
                                    placeholder="nama@email.com" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                                <i class="bi bi-arrow-right-circle me-1"></i> Lanjutkan Verifikasi
                            </button>
                        </form>
                    <?php else: ?>
                        <!-- Langkah 2: Verifikasi Sesuai Role yang Terdeteksi -->
                        <form method="post" action="<?= base_url('lupa-password') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="step" value="reset">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold small mb-0">Alamat Email</label>
                                    <a href="<?= base_url('lupa-password') ?>" class="small text-decoration-none">Ganti Email</a>
                                </div>
                                <input type="email" name="email" value="<?= esc($email) ?>" class="form-control bg-light" readonly>
                            </div>

                            <?php if ($detectedRole === 'pencaker'): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">NIK KTP (16 Digit) <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" value="<?= esc(old('nik')) ?>" maxlength="16" class="form-control"
                                        placeholder="Masukkan 16 digit NIK KTP Anda" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Nomor HP / WhatsApp Terdaftar <span class="text-danger">*</span></label>
                                    <input type="text" name="hp" value="<?= esc(old('hp')) ?>" class="form-control"
                                        placeholder="Contoh: 081234567890" required>
                                </div>
                            <?php elseif ($detectedRole === 'perusahaan'): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Nomor Booth Event <span class="text-danger">*</span></label>
                                    <input type="text" name="no_booth" value="<?= esc(old('no_booth')) ?>" class="form-control"
                                        placeholder="Contoh: A-01" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Nomor HP HRD Terdaftar <span class="text-danger">*</span></label>
                                    <input type="text" name="hp" value="<?= esc(old('hp')) ?>" class="form-control"
                                        placeholder="Contoh: 081234567890" required>
                                </div>
                            <?php endif; ?>

                            <hr class="my-3">

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Kata Sandi Baru <span class="text-danger">*</span></label>
                                <input type="password" name="password_baru" minlength="6" class="form-control"
                                    placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                                <input type="password" name="konfirmasi_password" minlength="6" class="form-control"
                                    placeholder="Ulangi kata sandi baru" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                                <i class="bi bi-key-fill me-1"></i> Reset Kata Sandi Sekarang
                            </button>
                        </form>
                    <?php endif; ?>

                    <div class="text-center mt-4 pt-3 border-top small">
                        Sudah ingat kata sandi? <a href="<?= base_url('login') ?>" class="fw-semibold">Kembali ke Halaman Masuk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
