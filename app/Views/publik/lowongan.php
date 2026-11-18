<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="card rounded-4 border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3">Cari Lowongan Kerja</h4>
            <form method="get" action="<?= base_url('lowongan') ?>" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Nama jabatan, perusahaan, atau kata kunci...">
                </div>
                <div class="col-md-3">
                    <select name="pendidikan" class="form-select">
                        <option value="">-- Semua Jenjang Pendidikan --</option>
                        <?php foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2'] as $p): ?>
                            <option value="<?= $p ?>" <?= $pendidikan === $p ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="disabilitas" value="1" id="chkDis" <?= $disabilitas === '1' ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="chkDis">Ramah Disabilitas</label>
                    </div>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary fw-semibold"><i class="bi bi-search me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <?php if (empty($lowonganList)): ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada lowongan yang sesuai dengan kriteria pencarian Anda.</div>
            </div>
        <?php endif; ?>
        <?php foreach ($lowonganList as $l): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 rounded-4 card-hover">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-soft-primary">Booth <?= esc($l['no_booth']) ?></span>
                            <?php if (! empty($l['ramah_disabilitas'])): ?>
                                <span class="badge badge-soft-success"><i class="bi bi-universal-access me-1"></i>Ramah Disabilitas</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <?php if (! empty($l['logo'])): ?>
                                <img src="<?= base_url('media/logo/' . $l['logo']) ?>" alt="Logo <?= esc($l['nama_perusahaan']) ?>" class="rounded border p-1 bg-white flex-shrink-0" style="width: 48px; height: 48px; object-fit: contain;">
                            <?php endif; ?>
                            <div>
                                <h5 class="fw-bold mb-1"><?= esc($l['jabatan']) ?></h5>
                                <div class="text-primary fw-semibold small"><?= esc($l['nama_perusahaan']) ?></div>
                            </div>
                        </div>
                        <p class="small text-muted mb-3"><?= esc(character_limiter($l['deskripsi'], 110)) ?></p>
                        <div class="small text-secondary mb-3 mt-auto">
                            <div><i class="bi bi-mortarboard me-1"></i> Min. <?= esc($l['pendidikan_min']) ?> &bull; Usia maks: <?= $l['usia_max'] ? esc($l['usia_max']) . ' th' : 'Bebas' ?></div>
                            <div><i class="bi bi-people me-1"></i> Kuota: <strong><?= (int)$l['kuota'] ?> orang</strong></div>
                            <div><i class="bi bi-geo-alt me-1"></i> <?= esc($l['lokasi_kerja']) ?></div>
                        </div>
                        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="small fw-semibold text-success">
                                <?php if ($l['gaji_min'] && $l['gaji_max']): ?>
                                    Rp <?= number_format($l['gaji_min'], 0, ',', '.') ?> - <?= number_format($l['gaji_max'], 0, ',', '.') ?>
                                <?php elseif ($l['gaji_min']): ?>
                                    Rp <?= number_format($l['gaji_min'], 0, ',', '.') ?>+
                                <?php else: ?>
                                    Gaji Kompetitif
                                <?php endif; ?>
                            </span>
                            <a href="<?= base_url('lowongan/' . $l['id']) ?>" class="btn btn-sm btn-primary px-3">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
