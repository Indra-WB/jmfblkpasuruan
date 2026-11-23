<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div class="small text-muted">
        <span class="badge bg-success me-1"><i class="bi bi-broadcast"></i> LIVE</span>
        Pembaruan otomatis setiap 15 detik &bull; Terakhir diperbarui: <strong
            id="lblLastUpdate"><?= date('H:i:s') ?></strong>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('pimpinan/laporan') ?>" class="btn btn-sm btn-primary"><i class="bi bi-table me-1"></i>
            Detail Laporan</a>
        <a href="<?= base_url('pimpinan/laporan/pdf/lengkap') ?>" target="_blank" class="btn btn-sm btn-danger"><i
                class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF Berkop BLK</a>
        <a href="<?= base_url('pimpinan/laporan/excel/lengkap') ?>" class="btn btn-sm btn-success"><i
                class="bi bi-file-earmark-excel me-1"></i> Export Excel</a>
    </div>
</div>

<!-- Kartu Ringkasan Utama -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card stat-card p-3">
            <div class="small text-muted">Pendaftar</div>
            <h3 class="fw-bold text-primary mb-0" id="stPendaftar"><?= number_format($stats['totalPendaftar'] ?? 0) ?>
            </h3>
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
    <div class="col-lg-4">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Grafik Terdaftar vs Hadir per Sesi Kedatangan</h6>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="w-100" style="position: relative; height: 220px;">
                    <canvas id="chartSesi"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Distribusi Pendidikan Pencari Kerja</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="w-100" style="position: relative; height: 220px;">
                    <canvas id="chartPendidikan"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card stat-card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Grafik Pelamar vs Diterima</h6>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="w-100" style="position: relative; height: 220px;">
                    <canvas id="chartPelamarDiterima"></canvas>
                </div>
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
                <tbody id="tbPerPerusahaan">
                    <?php
                        $totLowongan = 0;
                        $totKuota    = 0;
                        $totPelamar  = 0;
                        $totLolos    = 0;
                        $totPanggil  = 0;
                        $totDiterima = 0;
                    ?>
                    <?php foreach ($stats['perPerusahaan'] ?? [] as $pr): ?>
                        <?php
                            $totLowongan += (int) $pr['jml_lowongan'];
                            $totKuota    += (int) $pr['total_kuota'];
                            $totPelamar  += (int) $pr['total_pelamar'];
                            $totLolos    += (int) $pr['lolos_berkas'];
                            $totPanggil  += (int) $pr['panggil_tes'];
                            $totDiterima += (int) $pr['diterima'];
                        ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= esc($pr['no_booth']) ?></span></td>
                            <td class="fw-bold"><?= esc($pr['nama_perusahaan']) ?></td>
                            <td class="text-center"><?= (int) $pr['jml_lowongan'] ?></td>
                            <td class="text-center"><?= (int) $pr['total_kuota'] ?></td>
                            <td class="text-center fw-bold text-primary"><?= (int) $pr['total_pelamar'] ?></td>
                            <td class="text-center"><?= (int) $pr['lolos_berkas'] ?></td>
                            <td class="text-center"><?= (int) $pr['panggil_tes'] ?></td>
                            <td class="text-center fw-bold text-success table-success"><?= (int) $pr['diterima'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold" id="tfPerPerusahaan">
                    <tr>
                        <td colspan="2" class="text-end">Total Keseluruhan</td>
                        <td class="text-center"><?= number_format($totLowongan) ?></td>
                        <td class="text-center"><?= number_format($totKuota) ?></td>
                        <td class="text-center text-primary"><?= number_format($totPelamar) ?></td>
                        <td class="text-center"><?= number_format($totLolos) ?></td>
                        <td class="text-center"><?= number_format($totPanggil) ?></td>
                        <td class="text-center text-success table-success"><?= number_format($totDiterima) ?></td>
                    </tr>
                </tfoot>
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

    const chartSesi = new Chart(document.getElementById('chartSesi'), {
        type: 'bar',
        data: {
            labels: sesiData.map(s => s.nama),
            datasets: [
                { label: 'Terdaftar', data: sesiData.map(s => s.terdaftar), backgroundColor: '#3b82f6' },
                { label: 'Hadir (Check-in)', data: sesiData.map(s => s.hadir), backgroundColor: '#16a34a' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    const chartPendidikan = new Chart(document.getElementById('chartPendidikan'), {
        type: 'doughnut',
        data: {
            labels: pendData.map(p => p.pendidikan),
            datasets: [{
                data: pendData.map(p => p.jml),
                backgroundColor: ['#1d4ed8', '#0284c7', '#16a34a', '#ca8a04', '#dc2626', '#9333ea', '#64748b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });

    const chartPelamarDiterima = new Chart(document.getElementById('chartPelamarDiterima'), {
        type: 'bar',
        data: {
            labels: ['Total Pelamar', 'Total Diterima Kerja'],
            datasets: [{
                label: 'Jumlah Orang',
                data: [<?= (int) ($stats['totalLamaran'] ?? 0) ?>, <?= (int) ($stats['totalDiterima'] ?? 0) ?>],
                backgroundColor: ['#0284c7', '#16a34a'],
                borderRadius: 6,
                barPercentage: 0.55
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // Auto-refresh numbers & charts every 15s
    setInterval(function () {
        $.getJSON('<?= base_url('pimpinan/realtime-stats') ?>', function (res) {
            if (!res.ok) return;
            $('#lblLastUpdate').text(res.waktu);
            const s = res.stats;
            $('#stPendaftar').text(s.totalPendaftar);
            $('#stHadir').text(s.totalHadir);
            $('#stPerusahaan').text(s.totalPerusahaan);
            $('#stKuota').text(s.totalKuota);
            $('#stLamaran').text(s.totalLamaran);
            $('#stDiterima').text(s.totalDiterima);

            chartPelamarDiterima.data.datasets[0].data = [s.totalLamaran, s.totalDiterima];
            chartPelamarDiterima.update();

            if (s.perPerusahaan && $('#tbPerPerusahaan').length) {
                let tbody = '';
                let totLow = 0, totKuo = 0, totPel = 0, totLol = 0, totPan = 0, totDit = 0;
                s.perPerusahaan.forEach(function (pr) {
                    const low = Number(pr.jml_lowongan || 0);
                    const kuo = Number(pr.total_kuota || 0);
                    const pel = Number(pr.total_pelamar || 0);
                    const lol = Number(pr.lolos_berkas || 0);
                    const pan = Number(pr.panggil_tes || 0);
                    const dit = Number(pr.diterima || 0);

                    totLow += low; totKuo += kuo; totPel += pel; totLol += lol; totPan += pan; totDit += dit;

                    const booth = $('<div>').text(pr.no_booth || '').html();
                    const nama = $('<div>').text(pr.nama_perusahaan || '').html();

                    tbody += `<tr>
                        <td><span class="badge bg-primary">${booth}</span></td>
                        <td class="fw-bold">${nama}</td>
                        <td class="text-center">${low}</td>
                        <td class="text-center">${kuo}</td>
                        <td class="text-center fw-bold text-primary">${pel}</td>
                        <td class="text-center">${lol}</td>
                        <td class="text-center">${pan}</td>
                        <td class="text-center fw-bold text-success table-success">${dit}</td>
                    </tr>`;
                });
                $('#tbPerPerusahaan').html(tbody);
                $('#tfPerPerusahaan').html(`<tr>
                    <td colspan="2" class="text-end">Total Keseluruhan</td>
                    <td class="text-center">${totLow.toLocaleString('id-ID')}</td>
                    <td class="text-center">${totKuo.toLocaleString('id-ID')}</td>
                    <td class="text-center text-primary">${totPel.toLocaleString('id-ID')}</td>
                    <td class="text-center">${totLol.toLocaleString('id-ID')}</td>
                    <td class="text-center">${totPan.toLocaleString('id-ID')}</td>
                    <td class="text-center text-success table-success">${totDit.toLocaleString('id-ID')}</td>
                </tr>`);
            }
        });
    }, 15000);
</script>
<?= $this->endSection() ?>