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
                        <h4 class="fw-bold mb-1">Masuk Portal JMF</h4>
                        <p class="small text-muted mb-0">Pencari Kerja, Perusahaan, Petugas, Pimpinan & Admin</p>
                    </div>

                    <form method="post" action="<?= base_url('login') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" name="email" value="<?= esc(old('email')) ?>" class="form-control"
                                placeholder="nama@email.com" required autofocus>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-semibold small mb-1">Kata Sandi</label>
                                <a href="<?= base_url('lupa-password') ?>" class="small text-decoration-none fw-semibold">Lupa Kata Sandi?</a>
                            </div>
                            <input type="password" name="password" class="form-control"
                                placeholder="Masukkan kata sandi" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small">
                        Belum punya akun pencari kerja? <a href="<?= base_url('daftar') ?>" class="fw-semibold">Daftar
                            di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>