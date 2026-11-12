<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" id="usrTitle">Tambah Akun Petugas / Pimpinan</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/pengguna/simpan') ?>" id="frmUser">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="u_id" value="0">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" id="u_nama" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Alamat Email</label>
                        <input type="email" name="email" id="u_email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Peran (Role)</label>
                        <select name="role" id="u_role" class="form-select form-select-sm">
                            <option value="petugas">Petugas Registrasi (Meja Check-in)</option>
                            <option value="pimpinan">Pimpinan / Disnaker (Lihat Laporan)</option>
                            <option value="admin">Admin BLK (Panitia)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password (Kosongkan jika tidak diubah)</label>
                        <input type="text" name="password" id="u_pass" class="form-control form-control-sm" placeholder="Default: Petugas123!">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold">Simpan Akun</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Akun Internal (Admin, Petugas Registrasi & Pimpinan)</h6>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Login Terakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="fw-bold"><?= esc($u['nama']) ?></td>
                                <td><?= esc($u['email']) ?></td>
                                <td><span class="badge bg-dark"><?= strtoupper(esc($u['role'])) ?></span></td>
                                <td><?= esc($u['last_login'] ?: '-') ?></td>
                                <td><span class="badge <?= $u['is_active'] ? 'bg-success' : 'bg-danger' ?>"><?= $u['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick='editUsr(<?= json_encode($u) ?>)'><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function editUsr(u) {
        $('#usrTitle').text('Edit Akun: ' + u.nama);
        $('#u_id').val(u.id);
        $('#u_nama').val(u.nama);
        $('#u_email').val(u.email);
        $('#u_role').val(u.role);
        $('#u_pass').val('');
    }
</script>
<?= $this->endSection() ?>
