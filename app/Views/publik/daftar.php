<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary mb-2">REGISTRASI PESERTA</span>
                        <h4 class="fw-bold mb-1">Daftar Akun Pencari Kerja</h4>
                        <p class="small text-muted mb-0">Akun ini tetap tersimpan dan dapat digunakan kembali untuk event Job Market Fair tahun berikutnya.</p>
                    </div>

                    <form method="post" action="<?= base_url('daftar') ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">NIK (16 Digit Sesuai KTP) <span class="text-danger">*</span></label>
                                <input type="text" name="nik" maxlength="16" minlength="16" pattern="\d{16}" value="<?= esc(old('nik')) ?>" class="form-control" placeholder="3514xxxxxxxxxxxx" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" value="<?= esc(old('nama')) ?>" class="form-control" placeholder="Sesuai KTP & Ijazah" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jk" class="form-select" required>
                                    <option value="L" <?= old('jk') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('jk') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select name="pendidikan" class="form-select" required>
                                    <?php foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2'] as $p): ?>
                                        <option value="<?= $p ?>" <?= (old('pendidikan') ?: 'SMK') === $p ? 'selected' : '' ?>><?= $p ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">No. HP / WhatsApp Aktif <span class="text-danger">*</span></label>
                                <input type="text" name="hp" value="<?= esc(old('hp')) ?>" class="form-control" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Alamat Email Aktif <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="<?= esc(old('email')) ?>" class="form-control" placeholder="Untuk notifikasi panggilan tes" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Buat Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password" name="password" minlength="6" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check bg-light rounded-3 p-3 border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="setuju_pdp" value="1" id="chkPdp" required>
                                    <label class="form-check-label small" for="chkPdp">
                                        Saya menyetujui bahwa data pribadi, pas foto, dan link berkas lamaran yang saya isikan digunakan oleh <strong>UPT BLK Pasuruan</strong> serta <strong>Perusahaan Peserta Job Market Fair</strong> yang saya lamar untuk keperluan seleksi rekrutmen dan pelaporan penempatan tenaga kerja sesuai UU Perlindungan Data Pribadi No. 27 Tahun 2022.
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                                    <i class="bi bi-person-check-fill me-1"></i> Buat Akun & Lanjutkan Isi Profil
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small">
                        Sudah punya akun? <a href="<?= base_url('login') ?>" class="fw-semibold">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
