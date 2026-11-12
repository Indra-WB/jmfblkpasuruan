<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">Daftar Seluruh Lowongan Kerja Perusahaan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle small datatable">
                <thead class="table-light">
                    <tr>
                        <th>Booth</th>
                        <th>Perusahaan</th>
                        <th>Jabatan Lowongan</th>
                        <th>Pendidikan & Gender</th>
                        <th class="text-center">Kuota</th>
                        <th class="text-center">Pelamar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowonganList as $l): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= esc($l['no_booth']) ?></span></td>
                            <td class="fw-semibold"><?= esc($l['nama_perusahaan']) ?></td>
                            <td>
                                <div class="fw-bold"><?= esc($l['jabatan']) ?></div>
                                <?php if ($l['ramah_disabilitas']): ?>
                                    <span class="badge bg-success-subtle text-success">Ramah Disabilitas</span>
                                <?php endif; ?>
                            </td>
                            <td>Min. <?= esc($l['pendidikan_min']) ?> (<?= esc($l['jenis_kelamin']) ?>)</td>
                            <td class="text-center"><?= (int)$l['kuota'] ?></td>
                            <td class="text-center fw-bold"><?= (int)$l['jml_pelamar'] ?></td>
                            <td><span class="badge <?= $l['is_active'] ? 'bg-success' : 'bg-secondary' ?>"><?= $l['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                            <td>
                                <form method="post" action="<?= base_url('admin/lowongan/toggle/' . $l['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary"><?= $l['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
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
