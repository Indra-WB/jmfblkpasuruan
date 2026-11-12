<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-send-check text-primary me-1"></i> Riwayat & Status Seleksi Lamaran</h6>
        <a href="<?= base_url('pencaker/scan-booth') ?>" class="btn btn-sm btn-primary"><i class="bi bi-qr-code-scan me-1"></i> Scan QR Booth Lagi</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead class="table-light">
                    <tr>
                        <th>Waktu Lamar</th>
                        <th>Perusahaan & Booth</th>
                        <th>Posisi Jabatan</th>
                        <th>Status Seleksi</th>
                        <th>Jadwal & Lokasi Tes / Catatan HRD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lamaran as $lm): ?>
                        <?php
                            $badge = match($lm['status']) {
                                'LolosBerkas' => 'bg-info text-dark',
                                'PanggilTes'  => 'bg-warning text-dark',
                                'Diterima'    => 'bg-success',
                                'Ditolak'     => 'bg-danger',
                                default       => 'bg-secondary',
                            };
                        ?>
                        <tr>
                            <td class="small"><?= esc(date('d/m/Y H:i', strtotime($lm['created_at']))) ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($lm['nama_perusahaan']) ?></div>
                                <span class="badge bg-light text-dark border">Booth <?= esc($lm['no_booth']) ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= esc($lm['jabatan']) ?></div>
                                <small class="text-muted"><?= esc($lm['lokasi_kerja']) ?></small>
                            </td>
                            <td><span class="badge <?= $badge ?> px-3 py-2"><?= esc($lm['status']) ?></span></td>
                            <td class="small">
                                <?php if (! empty($lm['jadwal_tes'])): ?>
                                    <div class="fw-semibold text-primary">
                                        <i class="bi bi-calendar-event me-1"></i> Jadwal Tes: <?= esc(date('d M Y H:i', strtotime($lm['jadwal_tes']))) ?> WIB
                                    </div>
                                    <div><i class="bi bi-geo-alt me-1"></i> Lokasi: <?= esc($lm['lokasi_tes'] ?: '-') ?></div>
                                <?php endif; ?>
                                <?php if (! empty($lm['catatan'])): ?>
                                    <div class="text-muted mt-1"><?= esc($lm['catatan']) ?></div>
                                <?php elseif (empty($lm['jadwal_tes'])): ?>
                                    <span class="text-muted">Menunggu proses seleksi HRD</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
