<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-award text-success me-1"></i> Rekapitulasi Hasil Seleksi & Penempatan
            Kerja Seluruh Perusahaan per Lowongan Jabatan</h6>
        <a href="<?= base_url('pimpinan/penempatan/excel') ?>" class="btn btn-sm btn-success fw-semibold">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle datatable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Booth</th>
                        <th>Perusahaan</th>
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
                    <?php
                    $totKuota = 0;
                    $totPelamar = 0;
                    $totLolos = 0;
                    $totPanggil = 0;
                    $totTerima = 0;
                    $totTolak = 0;
                    ?>
                    <?php foreach ($rekap as $r): ?>
                        <?php
                        $kuota = (int) ($r['kuota'] ?? 0);
                        $pelamar = (int) ($r['total_pelamar'] ?? 0);
                        $lolos = (int) ($r['lolos_berkas'] ?? 0);
                        $panggil = (int) ($r['panggil_tes'] ?? 0);
                        $terima = (int) ($r['diterima'] ?? 0);
                        $tolak = (int) ($r['ditolak'] ?? 0);
                        $persen = $kuota > 0 ? round(($terima / $kuota) * 100, 1) : 0;

                        $totKuota += $kuota;
                        $totPelamar += $pelamar;
                        $totLolos += $lolos;
                        $totPanggil += $panggil;
                        $totTerima += $terima;
                        $totTolak += $tolak;
                        ?>
                        <tr>
                            <td class="text-center"><span class="badge bg-primary"><?= esc($r['no_booth'] ?? '-') ?></span>
                            </td>
                            <td class="fw-semibold"><?= esc($r['nama_perusahaan']) ?></td>
                            <td class="fw-bold">
                                <?php if (!empty($r['jabatan'])): ?>
                                    <?= esc($r['jabatan']) ?>
                                <?php else: ?>
                                    <span class="text-muted fw-normal fst-italic">Belum ada lowongan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= $kuota ?></td>
                            <td class="text-center"><?= $pelamar ?></td>
                            <td class="text-center"><?= $lolos ?></td>
                            <td class="text-center"><?= $panggil ?></td>
                            <td class="text-center fw-bold text-success table-success"><?= $terima ?></td>
                            <td class="text-center text-danger"><?= $tolak ?></td>
                            <td class="text-center fw-semibold"><?= $persen ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <?php $totPersen = $totKuota > 0 ? round(($totTerima / $totKuota) * 100, 1) : 0; ?>
                    <tr>
                        <td colspan="3" class="text-end">Total Keseluruhan</td>
                        <td class="text-center"><?= number_format($totKuota) ?></td>
                        <td class="text-center"><?= number_format($totPelamar) ?></td>
                        <td class="text-center"><?= number_format($totLolos) ?></td>
                        <td class="text-center"><?= number_format($totPanggil) ?></td>
                        <td class="text-center text-success table-success"><?= number_format($totTerima) ?></td>
                        <td class="text-center text-danger"><?= number_format($totTolak) ?></td>
                        <td class="text-center"><?= $totPersen ?>%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>