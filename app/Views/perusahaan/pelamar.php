<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card mb-3">
    <div class="card-body py-3">
        <form method="get" action="<?= base_url('perusahaan/pelamar') ?>" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Filter Jabatan</label>
                <select name="lowongan_id" class="form-select form-select-sm">
                    <option value="">-- Semua Jabatan --</option>
                    <?php foreach ($lowonganOpts as $lo): ?>
                        <option value="<?= $lo['id'] ?>" <?= $lowonganFilter == $lo['id'] ? 'selected' : '' ?>><?= esc($lo['jabatan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Pendidikan</label>
                <select name="pendidikan" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2'] as $p): ?>
                        <option value="<?= $p ?>" <?= $pendidikanFilter === $p ? 'selected' : '' ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Gender</label>
                <select name="jk" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="L" <?= $jkFilter === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= $jkFilter === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Status Seleksi</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php foreach (['Dikirim','LolosBerkas','PanggilTes','Diterima','Ditolak'] as $st): ?>
                        <option value="<?= $st ?>" <?= $statusFilter === $st ? 'selected' : '' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i> Saring</button>
                <a href="<?= base_url('perusahaan/pelamar/export-excel') ?>" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
            </div>
        </form>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <form method="post" action="<?= base_url('perusahaan/pelamar/status-massal') ?>">
            <?= csrf_field() ?>
            <?php if (! $seleksiClosed): ?>
                <div class="row g-2 align-items-center bg-light border rounded-3 p-2 mb-3">
                    <div class="col-auto small fw-bold">Aksi Massal (Pelamar Terpilih):</div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="LolosBerkas">Lolos Berkas</option>
                            <option value="PanggilTes">Panggil Tes/Interview</option>
                            <option value="Diterima">Diterima Kerja</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="datetime-local" name="jadwal_tes" class="form-control form-control-sm" title="Jadwal Tes (Opsional)">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="lokasi_tes" class="form-control form-control-sm" placeholder="Lokasi Tes (Opsional)">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan HRD">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-dark fw-semibold">Terapkan</button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle small datatable">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="chkAll"></th>
                            <th>Nama Pelamar & NIK</th>
                            <th>Jabatan Dilamar</th>
                            <th>Pendidikan</th>
                            <th>Domisili</th>
                            <th>Berkas PDF</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pelamarList as $pl): ?>
                            <?php
                                $badge = match($pl['status']) {
                                    'LolosBerkas' => 'bg-info text-dark',
                                    'PanggilTes'  => 'bg-warning text-dark',
                                    'Diterima'    => 'bg-success',
                                    'Ditolak'     => 'bg-danger',
                                    default       => 'bg-secondary',
                                };
                            ?>
                            <tr>
                                <td><input type="checkbox" name="lamaran_ids[]" value="<?= $pl['id'] ?>" class="chk-item"></td>
                                <td>
                                    <div class="fw-bold"><?= esc($pl['nama']) ?> (<?= esc($pl['jk']) ?>)</div>
                                    <div class="text-muted"><?= esc($pl['nik']) ?> &bull; <?= esc($pl['hp']) ?></div>
                                </td>
                                <td class="fw-semibold"><?= esc($pl['jabatan']) ?></td>
                                <td><?= esc($pl['pendidikan']) ?> - <?= esc($pl['jurusan']) ?></td>
                                <td><?= $pl['domisili'] === 'kab_pasuruan' ? 'Kec. ' . esc($pl['nama_kecamatan']) : esc($pl['kota_luar']) ?></td>
                                <td>
                                    <?php if (! empty($pl['link_berkas'])): ?>
                                        <a href="<?= esc($pl['link_berkas']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Buka PDF
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?= $badge ?>"><?= esc($pl['status']) ?></span></td>
                                <td>
                                    <a href="<?= base_url('perusahaan/pelamar/detail/' . $pl['id']) ?>" class="btn btn-sm btn-primary">Seleksi</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#chkAll').on('change', function() {
        $('.chk-item').prop('checked', $(this).is(':checked'));
    });
</script>
<?= $this->endSection() ?>
