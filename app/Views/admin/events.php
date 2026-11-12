<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" id="evFormTitle">Buat / Edit Event Tahunan</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/events/simpan') ?>" id="frmEvent">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="ev_id" value="0">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="ev_nama" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Tahun</label>
                            <input type="number" name="tahun" id="ev_tahun" value="<?= date('Y') ?>" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label small fw-semibold">Tanggal Acara (Hari-H)</label>
                            <input type="date" name="tanggal" id="ev_tanggal" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="ev_jam_mulai" value="08:00" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="ev_jam_selesai" value="16:00" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Lokasi Pelaksanaan</label>
                        <input type="text" name="lokasi" id="ev_lokasi" value="UPT BLK Pasuruan" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Deskripsi Event</label>
                        <textarea name="deskripsi" id="ev_deskripsi" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Batas Waktu Seleksi HRD</label>
                            <input type="date" name="batas_seleksi" id="ev_batas_seleksi" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Status Event</label>
                            <select name="status" id="ev_status" class="form-select form-select-sm">
                                <option value="aktif">Aktif</option>
                                <option value="draf">Draf</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Aturan Check-in di Luar Sesi</label>
                        <select name="aturan_luar_sesi" id="ev_aturan" class="form-select form-select-sm">
                            <option value="peringatan">Tampilkan Peringatan (Petugas Tetap Bisa Check-in)</option>
                            <option value="tolak">Tolak Check-in di Luar Jam Sesi</option>
                        </select>
                    </div>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pendaftaran_dibuka" value="1" id="ev_daftar" checked>
                            <label class="form-check-label small" for="ev_daftar">Pendaftaran Dibuka</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lamaran_dibuka" value="1" id="ev_lamar" checked>
                            <label class="form-check-label small" for="ev_lamar">Lamaran Booth Dibuka</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sesi_aktif" value="1" id="ev_sesi" checked>
                            <label class="form-check-label small" for="ev_sesi">Aktifkan Sesi Kedatangan</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3"><i class="bi bi-save me-1"></i> Simpan Event</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Event Job Market Fair</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Event & Tahun</th>
                                <th>Tanggal & Jam</th>
                                <th>Sesi Kedatangan</th>
                                <th>Batas Seleksi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $ev): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= esc($ev['nama']) ?></div>
                                        <small class="text-muted">Tahun <?= esc($ev['tahun']) ?></small>
                                    </td>
                                    <td><?= esc($ev['tanggal']) ?><br><small><?= substr($ev['jam_mulai'],0,5) ?>-<?= substr($ev['jam_selesai'],0,5) ?></small></td>
                                    <td>
                                        <span class="badge <?= $ev['sesi_aktif'] ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                            <?= $ev['sesi_aktif'] ? 'Aktif (' . $ev['aturan_luar_sesi'] . ')' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td><?= esc($ev['batas_seleksi'] ?: '-') ?></td>
                                    <td>
                                        <span class="badge <?= $ev['status'] === 'aktif' ? 'bg-success' : 'bg-secondary' ?>"><?= strtoupper(esc($ev['status'])) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick='editEvent(<?= json_encode($ev) ?>)'><i class="bi bi-pencil"></i> Edit</button>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function editEvent(ev) {
        $('#evFormTitle').text('Edit Event: ' + ev.nama);
        $('#ev_id').val(ev.id);
        $('#ev_nama').val(ev.nama);
        $('#ev_tahun').val(ev.tahun);
        $('#ev_tanggal').val(ev.tanggal);
        $('#ev_jam_mulai').val(ev.jam_mulai.substring(0,5));
        $('#ev_jam_selesai').val(ev.jam_selesai.substring(0,5));
        $('#ev_lokasi').val(ev.lokasi);
        $('#ev_deskripsi').val(ev.deskripsi);
        $('#ev_batas_seleksi').val(ev.batas_seleksi);
        $('#ev_status').val(ev.status);
        $('#ev_aturan').val(ev.aturan_luar_sesi);
        $('#ev_daftar').prop('checked', ev.pendaftaran_dibuka == 1);
        $('#ev_lamar').prop('checked', ev.lamaran_dibuka == 1);
        $('#ev_sesi').prop('checked', ev.sesi_aktif == 1);
    }
</script>
<?= $this->endSection() ?>
