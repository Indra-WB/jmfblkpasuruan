<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div class="small text-muted">
        <span class="badge bg-success me-1"><i class="bi bi-broadcast"></i> LIVE</span>
        Pembaruan otomatis setiap 15 detik &bull; Terakhir diperbarui: <strong id="lblLastUpdate"><?= date('H:i:s') ?></strong>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('pimpinan/laporan') ?>" class="btn btn-sm btn-primary"><i class="bi bi-table me-1"></i> Detail Laporan</a>
        <a href="<?= base_url('pimpinan/laporan/pdf/lengkap') ?>" target="_blank" class="btn btn-sm btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF Berkop BLK</a>
        <a href="<?= base_url('pimpinan/laporan/excel/lengkap') ?>" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel</a>
    </div>
</div>

<!-- Kartu Ringkasan Utama -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3">
            <div class="small text-muted">Pendaftar</div>
            <h3 class="fw-bold text-primary mb-0" id="stPendaftar"><?= number_format($stats['totalPendaftar'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3">
            <div class="small text-muted">Hadir (Check-in)</div>
            <h3 class="fw-bold text-success mb-0" id="stHadir"><?= number_format($stats['totalHadir'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3">
            <div class="small text-muted">Perusahaan</div>
            <h3 class="fw-bold mb-0" id="stPerusahaan"><?= number_format($stats['totalPerusahaan'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3">
            <div class="small text-muted">Kuota Lowongan</div>
            <h3 class="fw-bold text-warning mb-0" id="stKuota"><?= number_format($stats['totalKuota'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3">
            <div class="small text-muted">Lamaran Masuk</div>
            <h3 class="fw-bold text-info mb-0" id="stLamaran"><?= number_format($stats['totalLamaran'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3 bg-success text-white">
            <div class="small text-white-50">Diterima Kerja</div>
            <h3 class="fw-bold mb-0" id="stDiterima"><?= number_format($stats['totalDiterima'] ?? 0) ?></h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Grafik Terdaftar vs Hadir per Sesi Kedatangan</h6>
            </div>
            <div class="card-body">
                <canvas id="chartSesi" height="210"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Distribusi Pendidikan Pencari Kerja</h6>
            </div>
            <div class="card-body">
                <canvas id="chartPendidikan" height="210"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">Peringkat Perusahaan & Progres Penempatan Kerja</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Booth</th>
                        <th>Nama Perusahaan</th>
                        <th class="text-center">Lowongan</th>
                        <th class="text-center">Kuota</th>
                        <th class="text-center">Pelamar Masuk</th>
                        <th class="text-center">Lolos Berkas</th>
                        <th class="text-center">Panggil Tes</th>
                        <th class="text-center table-success">Diterima Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['perPerusahaan'] ?? [] as $pr): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= esc($pr['no_booth']) ?></span></td>
                            <td class="fw-bold"><?= esc($pr['nama_perusahaan']) ?></td>
                            <td class="text-center"><?= (int)$pr['jml_lowongan'] ?></td>
                            <td class="text-center"><?= (int)$pr['total_kuota'] ?></td>
                            <td class="text-center fw-bold text-primary"><?= (int)$pr['total_pelamar'] ?></td>
                            <td class="text-center"><?= (int)$pr['lolos_berkas'] ?></td>
                            <td class="text-center"><?= (int)$pr['panggil_tes'] ?></td>
                            <td class="text-center fw-bold text-success table-success"><?= (int)$pr['diterima'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const sesiData = <?= json_encode($stats['perSesi'] ?? []) ?>;
    const pendData = <?= json_encode($stats['demoPendidikan'] ?? []) ?>;

    new Chart(document.getElementById('chartSesi'), {
        type: 'bar',
        data: {
            labels: sesiData.map(s => s.nama),
            datasets: [
                { label: 'Terdaftar', data: sesiData.map(s => s.terdaftar), backgroundColor: '#3b82f6' },
                { label: 'Hadir (Check-in)', data: sesiData.map(s => s.hadir), backgroundColor: '#16a34a' }
            ]
        }
    });

    new Chart(document.getElementById('chartPendidikan'), {
        type: 'doughnut',
        data: {
            labels: pendData.map(p => p.pendidikan),
            datasets: [{
                data: pendData.map(p => p.jml),
                backgroundColor: ['#1d4ed8','#0284c7','#16a34a','#ca8a04','#dc2626','#9333ea','#64748b']
            }]
        }
    });

    // Auto-refresh numbers every 15s
    setInterval(function() {
        $.getJSON('<?= base_url('pimpinan/realtime-stats') ?>', function(res) {
            if (!res.ok) return;
            $('#lblLastUpdate').text(res.waktu);
            const s = res.stats;
            $('#stPendaftar').text(s.totalPendaftar);
            $('#stHadir').text(s.totalHadir);
            $('#stPerusahaan').text(s.totalPerusahaan);
            $('#stKuota').text(s.totalKuota);
            $('#stLamaran').text(s.totalLamaran);
            $('#stDiterima').text(s.totalDiterima);
        });
    }, 15000);
</script>
<?= $this->endSection() ?>
