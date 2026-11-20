<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-award text-success me-1"></i> Rekapitulasi Hasil Seleksi & Penempatan
            Kerja per Lowongan Jabatan</h6>
        <a href="<?= base_url('perusahaan/penempatan/excel') ?>" class="btn btn-sm btn-success fw-semibold">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Lowongan Jabatan</th>
                        <th class="text-center">Kuota Kebutuhan</th>
                        <th class="text-center">Total Pelamar</th>
                        <th class="text-center">Lolos Berkas</th>
                        <th class="text-center">Panggil Tes</th>
                        <th class="text-center table-success">Diterima Bekerja</th>
                        <th class="text-center">Ditolak</th>
                        <th class="text-center">% Penempatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rekap as $r): ?>
                        <?php $persen = $r['kuota'] > 0 ? round(($r['diterima'] / $r['kuota']) * 100, 1) : 0; ?>
                        <tr>
                            <td class="fw-bold"><?= esc($r['jabatan']) ?></td>
                            <td class="text-center"><?= (int) $r['kuota'] ?></td>
                            <td class="text-center"><?= (int) $r['total_pelamar'] ?></td>
                            <td class="text-center"><?= (int) $r['lolos_berkas'] ?></td>
                            <td class="text-center"><?= (int) $r['panggil_tes'] ?></td>
                            <td class="text-center fw-bold text-success table-success"><?= (int) $r['diterima'] ?></td>
                            <td class="text-center text-danger"><?= (int) $r['ditolak'] ?></td>
                            <td class="text-center fw-semibold"><?= $persen ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>