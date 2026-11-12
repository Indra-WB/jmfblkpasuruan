<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">Profil & Kontak HRD Perusahaan</h6>
    </div>
    <div class="card-body p-4">
        <form method="post" action="<?= base_url('perusahaan/profil') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="<?= esc($perusahaan['nama']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Bidang Usaha / Industri <span class="text-danger">*</span></label>
                    <input type="text" name="bidang_usaha" value="<?= esc($perusahaan['bidang_usaha']) ?>" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Kota / Kabupaten</label>
                    <input type="text" name="kota" value="<?= esc($perusahaan['kota']) ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Telepon Kantor</label>
                    <input type="text" name="telp" value="<?= esc($perusahaan['telp']) ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Website</label>
                    <input type="text" name="website" value="<?= esc($perusahaan['website']) ?>" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Alamat Lengkap Perusahaan</label>
                    <textarea name="alamat" rows="2" class="form-control"><?= esc($perusahaan['alamat']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Deskripsi Singkat Perusahaan</label>
                    <textarea name="deskripsi" rows="3" class="form-control"><?= esc($perusahaan['deskripsi']) ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Nama PIC / HRD</label>
                    <input type="text" name="nama_hrd" value="<?= esc($perusahaan['nama_hrd']) ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">No. HP / WA HRD</label>
                    <input type="text" name="hp_hrd" value="<?= esc($perusahaan['hp_hrd']) ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Logo Perusahaan (JPG/PNG maks 1 MB)</label>
                    <input type="file" name="logo" accept=".jpg,.jpeg,.png" class="form-control">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-save me-1"></i> Simpan Profil</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
