<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-chat-heart text-primary me-1"></i> Kuesioner Evaluasi & Kepuasan Pelaksanaan Job Market Fair</h6>
            </div>
            <div class="card-body p-4">
                <form method="post" action="">
                    <?= csrf_field() ?>
                    <?php foreach ($pertanyaan as $idx => $q): ?>
                        <?php $existing = $jawabanMap[$q['id']] ?? null; ?>
                        <div class="mb-4 pb-3 border-bottom">
                            <label class="form-label fw-semibold"><?= ($idx + 1) . '. ' . esc($q['pertanyaan']) ?></label>
                            <?php if ($q['tipe'] === 'skala'): ?>
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    <?php
                                        $labels = [1 => '1 - Sangat Kurang', 2 => '2 - Kurang', 3 => '3 - Cukup', 4 => '4 - Baik', 5 => '5 - Sangat Baik'];
                                        foreach ($labels as $val => $txt):
                                    ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="q_<?= $q['id'] ?>" id="q_<?= $q['id'] ?>_<?= $val ?>" value="<?= $val ?>" <?= ($existing['nilai'] ?? 5) == $val ? 'checked' : '' ?> required>
                                            <label class="form-check-label small" for="q_<?= $q['id'] ?>_<?= $val ?>"><?= $txt ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <textarea name="q_<?= $q['id'] ?>" rows="3" class="form-control" placeholder="Tuliskan saran dan masukan Anda..."><?= esc($existing['jawaban_teks'] ?? '') ?></textarea>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-send me-1"></i> Simpan Survei</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
