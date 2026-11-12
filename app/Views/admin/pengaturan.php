<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Identitas Instansi, Kop Laporan PDF & Pengaturan Email SMTP</h6>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= base_url('admin/pengaturan') ?>">
                    <?= csrf_field() ?>
                    <h6 class="fw-bold text-primary small text-uppercase mb-2">1. Identitas & Kop Surat UPT BLK Pasuruan</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Instansi</label>
                            <input type="text" name="nama_instansi" value="<?= esc($pengaturan['nama_instansi'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Instansi Induk (Disnaker)</label>
                            <input type="text" name="sub_instansi" value="<?= esc($pengaturan['sub_instansi'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Alamat Lengkap</label>
                            <input type="text" name="alamat_instansi" value="<?= esc($pengaturan['alamat_instansi'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Telepon</label>
                            <input type="text" name="telp_instansi" value="<?= esc($pengaturan['telp_instansi'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nama Kepala UPT BLK</label>
                            <input type="text" name="kepala_blk" value="<?= esc($pengaturan['kepala_blk'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">NIP Kepala UPT BLK</label>
                            <input type="text" name="nip_kepala_blk" value="<?= esc($pengaturan['nip_kepala_blk'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold text-primary small text-uppercase mb-2">2. Konfigurasi Email Resmi SMTP (`jmf@uptblkpasuruan.com`)</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?= esc($pengaturan['smtp_host'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">SMTP Port</label>
                            <input type="text" name="smtp_port" value="<?= esc($pengaturan['smtp_port'] ?? '465') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Enkripsi</label>
                            <input type="text" name="smtp_crypto" value="<?= esc($pengaturan['smtp_crypto'] ?? 'ssl') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email Pengirim (SMTP User)</label>
                            <input type="email" name="smtp_user" value="<?= esc($pengaturan['smtp_user'] ?? 'jmf@uptblkpasuruan.com') ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password SMTP</label>
                            <input type="password" name="smtp_pass" value="<?= esc($pengaturan['smtp_pass'] ?? '') ?>" class="form-control form-control-sm" placeholder="Isi password email hosting">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Nama Pengirim Email</label>
                            <input type="text" name="smtp_from_name" value="<?= esc($pengaturan['smtp_from_name'] ?? '') ?>" class="form-control form-control-sm">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-save me-1"></i> Simpan Pengaturan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card stat-card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Log Antrean Email (`email_queue`)</h6>
                <form method="post" action="<?= base_url('admin/email/proses') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-send me-1"></i> Kirim Antrean</button>
                </form>
            </div>
            <div class="card-body small">
                <p class="text-muted small">Untuk produksi di hosting, Anda juga dapat menjalankan Cron Job: <code>php spark email:kirim</code></p>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tujuan & Subjek</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($antrean as $em): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= esc($em['tujuan']) ?></div>
                                        <div class="text-muted"><?= esc($em['subjek']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $em['status'] === 'terkirim' ? 'bg-success' : ($em['status'] === 'gagal' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                            <?= esc($em['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
