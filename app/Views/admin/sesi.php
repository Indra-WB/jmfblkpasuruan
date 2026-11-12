<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<!-- Pengaturan Saklar Sesi Kedatangan -->
<div class="card stat-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center gy-3">
            <div class="col-lg-8">
                <form method="post" action="<?= base_url('admin/sesi/pengaturan') ?>" class="row g-3 align-items-end">
                    <?= csrf_field() ?>
                    <div class="col-md-5">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="sesi_aktif" value="1" id="swSesiAktif" <?= ! empty($activeEvent['sesi_aktif']) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="swSesiAktif">Aktifkan Pembagian Sesi Kedatangan</label>
                        </div>
                        <small class="text-muted">Fitur opsional untuk mencegah penumpukan pelamar di lokasi JMF.</small>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold mb-1">Aturan Check-in Peserta di Luar Jam Sesi</label>
                        <select name="aturan_luar_sesi" class="form-select form-select-sm">
                            <option value="peringatan" <?= ($activeEvent['aturan_luar_sesi'] ?? '') === 'peringatan' ? 'selected' : '' ?>>Peringatan Saja (Petugas Tetap Bisa Meloloskan)</option>
                            <option value="tolak" <?= ($activeEvent['aturan_luar_sesi'] ?? '') === 'tolak' ? 'selected' : '' ?>>Tolak Check-in di Luar Jam Sesi</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">Simpan</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 text-lg-end border-start-lg">
                <form method="post" action="<?= base_url('admin/sesi/bagi-otomatis') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold">
                        <i class="bi bi-shuffle me-1"></i> Bagi Otomatis Peserta Tanpa Sesi (<?= $tanpaSesiCount ?> org)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" id="sesiFormTitle">Tambah / Edit Sesi Kedatangan</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/sesi/simpan') ?>" id="frmSesi">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="s_id" value="0">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Nama Sesi <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="s_nama" placeholder="Contoh: Sesi 1 (Pagi)" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="s_tanggal" value="<?= esc($activeEvent['tanggal'] ?? date('Y-m-d')) ?>" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="s_mulai" value="08:00" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="s_selesai" value="10:30" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Batas Kuota Pendaftar <span class="text-danger">*</span></label>
                            <input type="number" name="kuota" id="s_kuota" value="1000" min="1" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Urutan</label>
                            <input type="number" name="urutan" id="s_urutan" value="1" min="1" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold"><i class="bi bi-save me-1"></i> Simpan Sesi</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#frmSesi')[0].reset();$('#s_id').val(0);$('#sesiFormTitle').text('Tambah Sesi Kedatangan');">Reset</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Sesi & Kuota Pendaftar</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Urutan</th>
                                <th>Nama Sesi</th>
                                <th>Tanggal & Jam</th>
                                <th class="text-center">Kuota Maks</th>
                                <th class="text-center">Terdaftar</th>
                                <th class="text-center">Sisa Kuota</th>
                                <th class="text-center">Hadir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sesiList as $s): ?>
                                <?php $sisa = max(0, (int)$s['kuota'] - (int)$s['terisi']); ?>
                                <tr>
                                    <td><?= (int)$s['urutan'] ?></td>
                                    <td class="fw-bold"><?= esc($s['nama']) ?></td>
                                    <td><?= esc($s['tanggal']) ?><br><span class="text-muted"><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_selesai'],0,5) ?> WIB</span></td>
                                    <td class="text-center fw-semibold"><?= number_format($s['kuota']) ?></td>
                                    <td class="text-center"><?= number_format($s['terisi']) ?></td>
                                    <td class="text-center fw-bold <?= $sisa > 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($sisa) ?></td>
                                    <td class="text-center text-primary fw-bold"><?= number_format($s['hadir']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick='editSesi(<?= json_encode($s) ?>)'><i class="bi bi-pencil"></i></button>
                                        <form method="post" action="<?= base_url('admin/sesi/hapus/' . $s['id']) ?>" class="d-inline" onsubmit="return confirm('Hapus sesi ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Peserta & Pemindahan Sesi oleh Admin -->
<div class="card stat-card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">Daftar Peserta Terdaftar & Pindah Sesi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle small datatable">
                <thead class="table-light">
                    <tr>
                        <th>No. Reg</th>
                        <th>Nama Peserta & NIK</th>
                        <th>Sesi Saat Ini</th>
                        <th>Status Check-in</th>
                        <th>Ubah Sesi Kedatangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesertaList as $ps): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc($ps['no_registrasi']) ?></td>
                            <td>
                                <div class="fw-bold"><?= esc($ps['nama']) ?></div>
                                <span class="text-muted"><?= esc($ps['nik']) ?> &bull; <?= esc($ps['hp']) ?></span>
                            </td>
                            <td><span class="badge bg-info text-dark"><?= esc($ps['nama_sesi'] ?: 'Belum Pilih Sesi') ?></span></td>
                            <td>
                                <?php if ($ps['checkin_at']): ?>
                                    <span class="badge bg-success">Hadir (<?= esc(date('H:i', strtotime($ps['checkin_at']))) ?>)</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum Check-in</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="<?= base_url('admin/sesi/pindah-peserta') ?>" class="d-flex gap-1">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="event_peserta_id" value="<?= $ps['id'] ?>">
                                    <select name="sesi_id" class="form-select form-select-sm" style="max-width: 180px;">
                                        <option value="">-- Tanpa Sesi --</option>
                                        <?php foreach ($sesiList as $s): ?>
                                            <option value="<?= $s['id'] ?>" <?= $ps['sesi_id'] == $s['id'] ? 'selected' : '' ?>><?= esc($s['nama']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Pindah</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function editSesi(s) {
        $('#sesiFormTitle').text('Edit Sesi: ' + s.nama);
        $('#s_id').val(s.id);
        $('#s_nama').val(s.nama);
        $('#s_tanggal').val(s.tanggal);
        $('#s_mulai').val(s.jam_mulai.substring(0,5));
        $('#s_selesai').val(s.jam_selesai.substring(0,5));
        $('#s_kuota').val(s.kuota);
        $('#s_urutan').val(s.urutan);
    }
</script>
<?= $this->endSection() ?>
