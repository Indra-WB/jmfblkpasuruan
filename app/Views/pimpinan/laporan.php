<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h5 class="fw-bold mb-0">Laporan Komprehensif Pelaksanaan Job Market Fair</h5>
        <small class="text-muted"><?= esc($activeEvent['nama'] ?? '') ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('pimpinan/laporan/pdf/lengkap') ?>" target="_blank" class="btn btn-danger fw-semibold">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan Resmi Berkop BLK (PDF)
        </a>
        <a href="<?= base_url('pimpinan/laporan/excel/lengkap') ?>" class="btn btn-success fw-semibold">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Unduh Rekap Excel
        </a>
    </div>
</div>

<!-- 1. Ringkasan Penempatan & Kehadiran -->
<div class="card stat-card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">1. Rekapitulasi Kehadiran, Lowongan & Tingkat Penempatan Kerja</h6>
    </div>
    <div class="card-body">
        <?php
            $persenHadir = ($stats['totalPendaftar'] ?? 0) > 0 ? round(($stats['totalHadir'] / $stats['totalPendaftar']) * 100, 1) : 0;
            $persenSerap = ($stats['totalKuota'] ?? 0) > 0 ? round(($stats['totalDiterima'] / $stats['totalKuota']) * 100, 1) : 0;
        ?>
        <div class="row g-2 g-md-3 text-center">
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2 p-md-3 h-100 d-flex flex-column justify-content-between align-items-center">
                    <div class="small text-muted">Pendaftar vs Hadir</div>
                    <h4 class="fw-bold my-1"><?= number_format($stats['totalPendaftar'] ?? 0) ?> / <?= number_format($stats['totalHadir'] ?? 0) ?></h4>
                    <span class="badge bg-primary-subtle text-primary">Kehadiran: <?= $persenHadir ?>%</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2 p-md-3 h-100 d-flex flex-column justify-content-between align-items-center">
                    <div class="small text-muted">Alumni BLK & Disabilitas</div>
                    <h4 class="fw-bold my-1"><?= (int)($stats['demoKhusus']['alumni_blk'] ?? 0) ?> <small class="fs-6">Alumni</small></h4>
                    <span class="badge bg-info-subtle text-dark">Disabilitas: <?= (int)($stats['demoKhusus']['disabilitas'] ?? 0) ?> org</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2 p-md-3 h-100 d-flex flex-column justify-content-between align-items-center">
                    <div class="small text-muted">Total Lamaran Masuk</div>
                    <h4 class="fw-bold my-1"><?= number_format($stats['totalLamaran'] ?? 0) ?></h4>
                    <span class="badge bg-warning-subtle text-dark">Panggil Tes: <?= number_format($stats['totalPanggil'] ?? 0) ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded-3 p-2 p-md-3 h-100 d-flex flex-column justify-content-between align-items-center bg-success bg-opacity-10 border-success">
                    <div class="small text-success fw-semibold">Kuota Lowongan vs Diterima</div>
                    <h4 class="fw-bold text-success my-1"><?= number_format($stats['totalKuota'] ?? 0) ?> / <?= number_format($stats['totalDiterima'] ?? 0) ?></h4>
                    <span class="badge bg-success">Tingkat Penempatan: <?= $persenSerap ?>%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Demografi Peserta -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">2. Demografi Wilayah (Kecamatan Kab. Pasuruan & Luar Daerah)</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kecamatan / Kabupaten/Kota Luar</th>
                            <th class="text-center">Jumlah Peserta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['demoWilayah'] ?? [] as $dw): ?>
                            <tr>
                                <td><?= esc($dw['wilayah']) ?></td>
                                <td class="text-center fw-bold"><?= (int)$dw['jml'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">3. Demografi Usia, Gender & Pendidikan</h6>
            </div>
            <div class="card-body small">
                <h6 class="fw-bold small">Kelompok Usia:</h6>
                <ul class="list-inline mb-3">
                    <li class="list-inline-item border rounded px-2 py-1">&le; 20 Th: <strong><?= (int)($stats['demoKhusus']['usia_20'] ?? 0) ?></strong></li>
                    <li class="list-inline-item border rounded px-2 py-1">21–25 Th: <strong><?= (int)($stats['demoKhusus']['usia_21_25'] ?? 0) ?></strong></li>
                    <li class="list-inline-item border rounded px-2 py-1">26–30 Th: <strong><?= (int)($stats['demoKhusus']['usia_26_30'] ?? 0) ?></strong></li>
                    <li class="list-inline-item border rounded px-2 py-1">&gt; 30 Th: <strong><?= (int)($stats['demoKhusus']['usia_30_plus'] ?? 0) ?></strong></li>
                </ul>

                <h6 class="fw-bold small">Tingkat Pendidikan:</h6>
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr><th>Pendidikan</th><th class="text-center">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['demoPendidikan'] ?? [] as $dp): ?>
                            <tr><td><?= esc($dp['pendidikan']) ?></td><td class="text-center fw-bold"><?= (int)$dp['jml'] ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 4. Hasil Survei Kepuasan -->
<div class="card stat-card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">4. Hasil Evaluasi Kuesioner Survei Kepuasan (Skala 1–5)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered small align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Responden</th>
                        <th>Pertanyaan / Indikator</th>
                        <th class="text-center">Jml Responden</th>
                        <th class="text-center">Rata-rata Nilai (1-5)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['surveiRekap'] ?? [] as $sr): ?>
                        <?php if ($sr['tipe'] !== 'skala') continue; ?>
                        <tr>
                            <td><span class="badge <?= $sr['target'] === 'pencaker' ? 'bg-primary' : 'bg-dark' ?>"><?= strtoupper(esc($sr['target'])) ?></span></td>
                            <td><?= esc($sr['pertanyaan']) ?></td>
                            <td class="text-center"><?= (int)$sr['responden'] ?></td>
                            <td class="text-center fw-bold text-success"><?= $sr['rata_nilai'] ?: '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
