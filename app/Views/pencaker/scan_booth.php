<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-camera-fill text-primary me-1"></i> Pindai Poster QR Booth Perusahaan</h6>
            </div>
            <div class="card-body p-4 text-center">
                <p class="small text-muted mb-3">Arahkan kamera HP Anda ke poster QR Code yang terpasang di Booth Perusahaan.</p>
                <div id="reader" class="mx-auto border rounded-3 overflow-hidden mb-3" style="max-width: 360px;"></div>
                <hr>
                <form id="frmManualToken" class="row g-2">
                    <div class="col-8">
                        <input type="text" id="txtBoothToken" class="form-control form-control-sm" placeholder="Atau masukkan Kode QR Booth manual...">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Buka Booth</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-shop text-success me-1"></i> Daftar Booth Perusahaan Aktif</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Jika kamera HP bermasalah, Anda juga dapat memilih booth perusahaan di bawah ini saat berada di depan booth:</p>
                <div class="list-group">
                    <?php foreach ($boothList as $b): ?>
                        <a href="<?= base_url('lamar/' . $b['qr_token']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary me-2">Booth <?= esc($b['no_booth']) ?></span>
                                <strong><?= esc($b['nama_perusahaan']) ?></strong>
                                <div class="small text-muted"><?= esc($b['bidang_usaha']) ?></div>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function handleScannedBooth(decodedText) {
        let token = decodedText.trim();
        if (token.includes('/lamar/')) {
            token = token.split('/lamar/').pop().split('?')[0].split('/')[0];
        }
        window.location.href = '<?= base_url('lamar') ?>/' + encodeURIComponent(token);
    }

    $('#frmManualToken').on('submit', function(e) {
        e.preventDefault();
        const t = $('#txtBoothToken').val().trim();
        if (t) handleScannedBooth(t);
    });

    try {
        const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 220, height: 220 } }, false);
        html5QrcodeScanner.render(handleScannedBooth);
    } catch (e) {
        console.warn(e);
    }
</script>
<?= $this->endSection() ?>
