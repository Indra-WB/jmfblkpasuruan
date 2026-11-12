<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" id="formTitle">Tambah / Edit Lowongan Kerja</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('perusahaan/lowongan/simpan') ?>" id="frmLowongan">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="low_id" value="0">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Nama Jabatan / Posisi <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="low_jabatan" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Pendidikan Min</label>
                            <select name="pendidikan_min" id="low_pendidikan" class="form-select form-select-sm">
                                <?php foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2'] as $p): ?>
                                    <option value="<?= $p ?>"><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Gender</label>
                            <select name="jenis_kelamin" id="low_jk" class="form-select form-select-sm">
                                <option value="L/P">Laki/Perempuan</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Usia Maks (Th)</label>
                            <input type="number" name="usia_max" id="low_usia" class="form-control form-control-sm" placeholder="Contoh: 28">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Kuota (Orang) <span class="text-danger">*</span></label>
                            <input type="number" name="kuota" id="low_kuota" value="5" min="1" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Gaji Min (Rp)</label>
                            <input type="number" name="gaji_min" id="low_gaji_min" class="form-control form-control-sm">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Gaji Max (Rp)</label>
                            <input type="number" name="gaji_max" id="low_gaji_max" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Lokasi Penempatan Kerja</label>
                        <input type="text" name="lokasi_kerja" id="low_lokasi" value="Kab. Pasuruan" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" id="low_deskripsi" rows="2" class="form-control form-control-sm" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Kualifikasi / Persyaratan <span class="text-danger">*</span></label>
                        <textarea name="kualifikasi" id="low_kualifikasi" rows="3" class="form-control form-control-sm" required></textarea>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="ramah_disabilitas" value="1" id="low_disabilitas">
                            <label class="form-check-label small" for="low_disabilitas">Ramah Disabilitas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="low_active" checked>
                            <label class="form-check-label small" for="low_active">Aktif Ditampilkan</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3"><i class="bi bi-save me-1"></i> Simpan Lowongan</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetForm()">Reset Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Lowongan Perusahaan Anda</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Jabatan</th>
                                <th>Syarat</th>
                                <th>Kuota</th>
                                <th>Pelamar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowonganList as $l): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= esc($l['jabatan']) ?></div>
                                        <?php if ($l['ramah_disabilitas']): ?>
                                            <span class="badge bg-success-subtle text-success">Ramah Disabilitas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>Min. <?= esc($l['pendidikan_min']) ?> (<?= esc($l['jenis_kelamin']) ?>)</td>
                                    <td><?= (int)$l['kuota'] ?></td>
                                    <td><a href="<?= base_url('perusahaan/pelamar?lowongan_id=' . $l['id']) ?>" class="fw-bold"><?= (int)$l['jml_pelamar'] ?> pelamar</a></td>
                                    <td>
                                        <span class="badge <?= $l['is_active'] ? 'bg-success' : 'bg-secondary' ?>"><?= $l['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick='editLowongan(<?= json_encode($l) ?>)'><i class="bi bi-pencil"></i></button>
                                        <form method="post" action="<?= base_url('perusahaan/lowongan/hapus/' . $l['id']) ?>" class="d-inline" onsubmit="return confirm('Hapus lowongan ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
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
    function editLowongan(l) {
        $('#formTitle').text('Edit Lowongan: ' + l.jabatan);
        $('#low_id').val(l.id);
        $('#low_jabatan').val(l.jabatan);
        $('#low_pendidikan').val(l.pendidikan_min);
        $('#low_jk').val(l.jenis_kelamin);
        $('#low_usia').val(l.usia_max);
        $('#low_kuota').val(l.kuota);
        $('#low_gaji_min').val(l.gaji_min);
        $('#low_gaji_max').val(l.gaji_max);
        $('#low_lokasi').val(l.lokasi_kerja);
        $('#low_deskripsi').val(l.deskripsi);
        $('#low_kualifikasi').val(l.kualifikasi);
        $('#low_disabilitas').prop('checked', l.ramah_disabilitas == 1);
        $('#low_active').prop('checked', l.is_active == 1);
    }
    function resetForm() {
        $('#formTitle').text('Tambah Lowongan Kerja Baru');
        $('#frmLowongan')[0].reset();
        $('#low_id').val(0);
    }
</script>
<?= $this->endSection() ?>
