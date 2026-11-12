<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card p-3 border-start border-4 border-primary">
            <div class="small text-muted">Total Peserta Terdaftar</div>
            <h3 class="fw-bold mb-0"><?= number_format($stats['total_terdaftar']) ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3 border-start border-4 border-success">
            <div class="small text-muted">Total Hadir (Sudah Check-in)</div>
            <h3 class="fw-bold text-success mb-0"><?= number_format($stats['total_hadir']) ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3 border-start border-4 border-warning">
            <div class="small text-muted">Belum Check-in</div>
            <h3 class="fw-bold text-warning mb-0"><?= number_format(max(0, $stats['total_terdaftar'] - $stats['total_hadir'])) ?></h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kamera Scanner & Input Kode -->
    <div class="col-lg-6">
        <div class="card stat-card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-qr-code-scan text-primary me-1"></i> Pemindai Kartu QR Peserta</h6>
            </div>
            <div class="card-body p-4">
                <div id="qrReader" class="mx-auto border rounded-3 overflow-hidden mb-3" style="max-width: 350px;"></div>

                <form id="frmVerifyKode" class="row g-2 mb-3">
                    <div class="col-8">
                        <input type="text" id="txtKode" class="form-control" placeholder="Ketik / Scan QR Token, No. Reg, atau NIK..." autofocus>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold"><i class="bi bi-search me-1"></i> Cek Peserta</button>
                    </div>
                </form>

                <!-- Hasil Verifikasi Peserta -->
                <div id="boxResult" style="display:none;" class="border rounded-3 p-3 bg-light">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <img id="resFoto" src="" style="width:70px;height:90px;object-fit:cover;display:none;" class="rounded border">
                        <div>
                            <span id="resNoReg" class="badge bg-primary mb-1"></span>
                            <h5 id="resNama" class="fw-bold mb-0"></h5>
                            <div id="resNik" class="small text-muted"></div>
                            <div id="resPendidikan" class="small text-secondary"></div>
                        </div>
                    </div>

                    <div id="resSesiAlert" class="alert py-2 px-3 small mb-3"></div>

                    <form method="post" action="<?= base_url('petugas/confirm') ?>" id="frmConfirmCheckin">
                        <?= csrf_field() ?>
                        <input type="hidden" name="event_peserta_id" id="resPesertaId">
                        <button type="submit" id="btnSubmitCheckin" class="btn btn-success btn-lg w-100 fw-bold">
                            <i class="bi bi-check2-circle me-1"></i> KONFIRMASI CHECK-IN SEKARANG
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pencarian Manual Nama / NIK -->
        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-lines-fill text-secondary me-1"></i> Pencarian Manual (Jika HP Peserta Mati / QR Rusak)</h6>
            </div>
            <div class="card-body">
                <div class="input-group mb-2">
                    <input type="text" id="txtCariNama" class="form-control form-control-sm" placeholder="Ketik minimal 3 huruf Nama atau NIK...">
                    <button type="button" id="btnCariNama" class="btn btn-sm btn-outline-primary">Cari</button>
                </div>
                <div id="listCariManual" class="list-group small"></div>
            </div>
        </div>
    </div>

    <!-- Statistik per Sesi & Riwayat Check-in Terakhir -->
    <div class="col-lg-6">
        <?php if (! empty($sesiStats)): ?>
            <div class="card stat-card mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-info me-1"></i> Kehadiran Peserta per Sesi Kedatangan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sesi</th>
                                <th>Jam</th>
                                <th class="text-center">Kuota</th>
                                <th class="text-center">Terdaftar</th>
                                <th class="text-center">Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sesiStats as $s): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($s['nama']) ?></td>
                                    <td class="small"><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_selesai'],0,5) ?></td>
                                    <td class="text-center"><?= (int)$s['kuota'] ?></td>
                                    <td class="text-center"><?= (int)$s['terdaftar'] ?></td>
                                    <td class="text-center fw-bold text-success"><?= (int)$s['hadir'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-check text-success me-1"></i> 10 Peserta Terakhir Check-in</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Jam</th>
                                <th>No. Reg</th>
                                <th>Nama & NIK</th>
                                <th>Sesi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentCheckin as $rc): ?>
                                <tr>
                                    <td class="fw-semibold text-success"><?= esc(date('H:i:s', strtotime($rc['checkin_at']))) ?></td>
                                    <td><?= esc($rc['no_registrasi']) ?></td>
                                    <td>
                                        <div class="fw-bold"><?= esc($rc['nama']) ?></div>
                                        <span class="text-muted"><?= esc($rc['nik']) ?></span>
                                    </td>
                                    <td>
                                        <?= esc($rc['nama_sesi'] ?: 'Bebas') ?>
                                        <?php if ($rc['checkin_luar_sesi']): ?>
                                            <span class="badge bg-warning text-dark">Luar Sesi</span>
                                        <?php endif; ?>
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
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    function verifyPeserta(kode) {
        if (!kode) return;
        $.post('<?= base_url('petugas/verify') ?>', {
            kode: kode,
            [csrfName]: csrfHash
        }, function(res) {
            if (res.csrfHash) csrfHash = res.csrfHash;
            if (!res.ok) {
                alert(res.msg);
                $('#boxResult').hide();
                return;
            }
            const p = res.peserta;
            const ev = res.evalSesi;
            $('#resPesertaId').val(p.id);
            $('#resNoReg').text(p.no_registrasi);
            $('#resNama').text(p.nama);
            $('#resNik').text('NIK: ' + p.nik + ' | HP: ' + p.hp);
            $('#resPendidikan').text(p.pendidikan + ' - ' + (p.jurusan || ''));

            if (res.fotoUrl) {
                $('#resFoto').attr('src', res.fotoUrl).show();
            } else {
                $('#resFoto').hide();
            }

            const $alert = $('#resSesiAlert');
            $alert.removeClass('alert-success alert-warning alert-danger').addClass('alert-' + ev.badge);
            let statusHtml = `<strong>Status Sesi:</strong> ${ev.label}`;
            if (p.checkin_at) {
                statusHtml += `<br><strong class="text-danger">SUDAH CHECK-IN PADA: ${p.checkin_at}</strong>`;
                $('#btnSubmitCheckin').prop('disabled', true).text('Sudah Check-in');
            } else if (!ev.allowed) {
                $('#btnSubmitCheckin').prop('disabled', true).text('Ditolak (Di Luar Sesi)');
            } else {
                $('#btnSubmitCheckin').prop('disabled', false).html('<i class="bi bi-check2-circle me-1"></i> KONFIRMASI CHECK-IN SEKARANG');
            }
            $alert.html(statusHtml);
            $('#boxResult').slideDown();
        }, 'json');
    }

    $('#frmVerifyKode').on('submit', function(e) {
        e.preventDefault();
        verifyPeserta($('#txtKode').val().trim());
    });

    $('#btnCariNama').on('click', function() {
        const q = $('#txtCariNama').val().trim();
        if (q.length < 3) return;
        $.getJSON('<?= base_url('petugas/cari') ?>', { q: q }, function(rows) {
            let html = '';
            rows.forEach(function(r) {
                const badge = r.checkin_at ? '<span class="badge bg-success">Sudah Hadir</span>' : '<span class="badge bg-warning text-dark">Belum Check-in</span>';
                html += `<button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="verifyPeserta('${r.qr_token}')">
                    <div><strong>${r.nama}</strong> (${r.nik})<br><small class="text-muted">${r.no_registrasi} | ${r.nama_sesi || '-'}</small></div>
                    ${badge}
                </button>`;
            });
            $('#listCariManual').html(html || '<div class="text-muted p-2">Tidak ditemukan.</div>');
        });
    });

    try {
        const scanner = new Html5QrcodeScanner("qrReader", { fps: 10, qrbox: { width: 220, height: 220 } }, false);
        scanner.render(function(decodedText) {
            $('#txtKode').val(decodedText);
            verifyPeserta(decodedText);
        });
    } catch (e) {
        console.warn(e);
    }
</script>
<?= $this->endSection() ?>
