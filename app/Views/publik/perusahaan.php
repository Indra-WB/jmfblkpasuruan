<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Perusahaan Peserta (Exhibitor)</h3>
        <p class="text-muted mb-0">Daftar perusahaan yang membuka rekrutmen pada <?= esc($activeEvent['nama'] ?? 'Job Market Fair') ?></p>
    </div>

    <div class="row g-3">
        <?php foreach ($perusahaan as $p): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 rounded-4 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary">Booth <?= esc($p['no_booth']) ?></span>
                            <span class="badge badge-soft-success"><?= (int)$p['jml_lowongan'] ?> Lowongan (Kuota <?= (int)$p['total_kuota'] ?>)</span>
                        </div>
                        <h5 class="fw-bold mb-1"><?= esc($p['nama']) ?></h5>
                        <div class="small text-primary fw-semibold mb-2"><?= esc($p['bidang_usaha']) ?></div>
                        <p class="small text-muted mb-3"><?= esc($p['deskripsi']) ?></p>
                        <div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i><?= esc($p['alamat']) ?></div>
                    </div>
                    <div class="card-footer bg-white border-top-0 px-4 pb-4 pt-0">
                        <a href="<?= base_url('lowongan?q=' . urlencode($p['nama'])) ?>" class="btn btn-sm btn-outline-primary w-100">Lihat Lowongan Perusahaan Ini</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
