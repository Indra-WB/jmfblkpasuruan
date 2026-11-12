<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Tambah Pertanyaan Survei</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/survei/simpan') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Target Responden</label>
                        <select name="target" class="form-select form-select-sm">
                            <option value="pencaker">Pencari Kerja (Peserta)</option>
                            <option value="perusahaan">Perusahaan (Exhibitor)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Pertanyaan / Indikator</label>
                        <textarea name="pertanyaan" rows="2" class="form-control form-control-sm" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Tipe Jawaban</label>
                            <select name="tipe" class="form-select form-select-sm">
                                <option value="skala">Skala Nilai 1 - 5</option>
                                <option value="teks">Isian Saran (Teks)</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Urutan</label>
                            <input type="number" name="urutan" value="8" class="form-control form-control-sm">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold">Tambah Pertanyaan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card stat-card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Pertanyaan Survei Kepuasan</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th>Target</th>
                            <th>Urutan</th>
                            <th>Pertanyaan</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pertanyaan as $q): ?>
                            <tr>
                                <td><span class="badge <?= $q['target'] === 'pencaker' ? 'bg-primary' : 'bg-dark' ?>"><?= strtoupper(esc($q['target'])) ?></span></td>
                                <td><?= (int)$q['urutan'] ?></td>
                                <td><?= esc($q['pertanyaan']) ?></td>
                                <td><?= esc($q['tipe']) ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('admin/survei/hapus/' . $q['id']) ?>" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm text-danger p-0"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Masukan & Saran Tertulis dari Peserta / Perusahaan</h6>
            </div>
            <div class="card-body small">
                <?php if (empty($saranList)): ?>
                    <div class="text-muted">Belum ada saran tertulis yang masuk.</div>
                <?php endif; ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($saranList as $s): ?>
                        <li class="list-group-item px-0">
                            <span class="badge bg-secondary me-1"><?= strtoupper(esc($s['target'])) ?></span>
                            <strong><?= esc($s['nama']) ?>:</strong> "<?= esc($s['jawaban_teks']) ?>"
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
