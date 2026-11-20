<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" id="compFormTitle">Input Perusahaan & Buat Akun HRD</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/perusahaan/simpan') ?>" id="frmPerusahaan">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="c_id" value="0">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="c_nama" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Bidang Usaha / Industri</label>
                        <input type="text" name="bidang_usaha" id="c_bidang" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nomor Booth Event</label>
                            <input type="text" name="no_booth" id="c_booth" placeholder="Mis: A-04" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kota / Kabupaten</label>
                            <input type="text" name="kota" id="c_kota" value="Kab. Pasuruan" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Alamat Perusahaan</label>
                        <textarea name="alamat" id="c_alamat" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nama HRD / PIC</label>
                            <input type="text" name="nama_hrd" id="c_hrd" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">No. HP HRD</label>
                            <input type="text" name="hp_hrd" id="c_hp_hrd" class="form-control form-control-sm">
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Email Login Perusahaan <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="c_email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password Login (Kosongkan = `Perusahaan123!`)</label>
                        <input type="text" name="password" id="c_pass" class="form-control form-control-sm" placeholder="Default: Perusahaan123!">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold"><i class="bi bi-save me-1"></i> Simpan Perusahaan</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#frmPerusahaan')[0].reset();$('#c_id').val(0);$('#compFormTitle').text('Input Perusahaan & Buat Akun HRD');">Reset</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Perusahaan Peserta, Nomor Booth & Cetak Poster QR</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Booth</th>
                                <th>Nama Perusahaan & HRD</th>
                                <th>Email Login</th>
                                <th class="text-center">Lowongan</th>
                                <th>Poster QR Booth</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($perusahaan as $p): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary fs-6"><?= esc($p['no_booth'] ?: '-') ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            <?= esc($p['nama']) ?>
                                            <?php if (! $p['is_active']): ?>
                                                <span class="badge bg-secondary ms-1">Nonaktif</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-muted"><?= esc($p['bidang_usaha']) ?> &bull; HRD: <?= esc($p['nama_hrd'] ?: '-') ?></div>
                                    </td>
                                    <td><code><?= esc($p['email_login']) ?></code></td>
                                    <td class="text-center fw-bold"><?= (int)$p['jml_lowongan'] ?></td>
                                    <td>
                                        <?php if ($p['ep_id']): ?>
                                            <a href="<?= base_url('admin/perusahaan/poster-qr/' . $p['ep_id']) ?>" target="_blank" class="btn btn-sm btn-danger">
                                                <i class="bi bi-qr-code me-1"></i> Cetak Poster PDF
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada booth</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="editComp(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <form method="post" action="<?= base_url('admin/perusahaan/reset-password/' . $p['user_id']) ?>" class="w-100" onsubmit="return confirm('Reset password perusahaan ini menjadi Perusahaan123! ?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-warning w-100" title="Reset Password"><i class="bi bi-key"></i></button>
                                            </form>
                                            <form method="post" action="<?= base_url('admin/perusahaan/toggle/' . $p['user_id']) ?>" class="w-100" onsubmit="return confirm('<?= $p['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> perusahaan ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm <?= $p['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?> w-100" title="<?= $p['is_active'] ? 'Nonaktifkan Perusahaan' : 'Aktifkan Perusahaan' ?>">
                                                    <i class="bi <?= $p['is_active'] ? 'bi-slash-circle' : 'bi-check-circle' ?>"></i>
                                                </button>
                                            </form>
                                        </div>
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

<?= $this->section('scripts') ?>
<script>
    function editComp(p) {
        $('#compFormTitle').text('Edit Perusahaan: ' + p.nama);
        $('#c_id').val(p.id);
        $('#c_nama').val(p.nama);
        $('#c_bidang').val(p.bidang_usaha);
        $('#c_booth').val(p.no_booth);
        $('#c_kota').val(p.kota);
        $('#c_alamat').val(p.alamat);
        $('#c_hrd').val(p.nama_hrd);
        $('#c_hp_hrd').val(p.hp_hrd);
        $('#c_email').val(p.email_login);
        $('#c_pass').val('');
        $('html, body').animate({ scrollTop: $('#frmPerusahaan').offset().top - 100 }, 250);
        $('#c_nama').trigger('focus');
    }
</script>
<?= $this->endSection() ?>
