<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card stat-card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0">Data Pencari Kerja Terdaftar</h6>
        <a href="<?= base_url('admin/pencaker/excel') ?>" class="btn btn-sm btn-success fw-semibold">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel (.xlsx)
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle small datatable">
                <thead class="table-light">
                    <tr>
                        <th>NIK & Nama Lengkap</th>
                        <th>Pendidikan</th>
                        <th>Domisili</th>
                        <th>Kontak</th>
                        <th>Link Berkas PDF</th>
                        <th>Status Event & Sesi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pencaker as $p): ?>
                        <tr>
                            <td>
                                <div class="fw-bold">
                                    <?= esc($p['nama']) ?> (<?= esc($p['jk']) ?>)
                                    <?php if (! $p['is_active']): ?>
                                        <span class="badge bg-secondary ms-1">Nonaktif</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-muted"><?= esc($p['nik']) ?></span>
                                <?php if ($p['disabilitas']): ?>
                                    <span class="badge bg-info text-dark">Disabilitas</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($p['pendidikan']) ?> - <?= esc($p['jurusan']) ?></td>
                            <td>
                                <?= $p['domisili'] === 'kab_pasuruan' ? esc(($p['nama_desa'] ?? '') . ', Kec. ' . ($p['nama_kecamatan'] ?? '')) : esc($p['kota_luar']) ?>
                            </td>
                            <td><?= esc($p['hp']) ?><br><small class="text-muted"><?= esc($p['email']) ?></small></td>
                            <td>
                                <?php if (! empty($p['link_berkas'])): ?>
                                    <a href="<?= esc($p['link_berkas']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum Isi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['no_registrasi']): ?>
                                    <div class="fw-semibold"><?= esc($p['no_registrasi']) ?> (<?= esc($p['nama_sesi'] ?: 'Bebas') ?>)</div>
                                    <?php if ($p['checkin_at']): ?>
                                        <span class="badge bg-success">Hadir Check-in</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Check-in</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Belum Daftar Event</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <form method="post" action="<?= base_url('admin/pencaker/reset-password/' . $p['user_id']) ?>" class="d-inline" onsubmit="return confirm('Reset password pencari kerja ini menjadi 123456 ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Reset Password (123456)">
                                        <i class="bi bi-key"></i>
                                    </button>
                                </form>
                                <form method="post" action="<?= base_url('admin/pencaker/toggle/' . $p['user_id']) ?>" class="d-inline" onsubmit="return confirm('<?= $p['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> akun pencari kerja ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm <?= $p['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>" title="<?= $p['is_active'] ? 'Nonaktifkan Akun' : 'Aktifkan Akun' ?>">
                                        <i class="bi <?= $p['is_active'] ? 'bi-slash-circle' : 'bi-check-circle' ?>"></i>
                                    </button>
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
