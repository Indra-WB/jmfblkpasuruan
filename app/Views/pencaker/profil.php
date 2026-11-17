<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-vcard text-primary me-1"></i> Biodata, Pas Foto & Link
                    Berkas Gabungan (PDF)</h6>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= base_url('pencaker/profil') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIK KTP</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($pencaker['nik']) ?>"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama" value="<?= esc($pencaker['nama']) ?>" class="form-control"
                                required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Tempat Lahir <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" value="<?= esc($pencaker['tempat_lahir']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tanggal Lahir <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="tgl_lahir" value="<?= esc($pencaker['tgl_lahir']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Jenis Kelamin <span
                                    class="text-danger">*</span></label>
                            <select name="jk" class="form-select" required>
                                <option value="L" <?= $pencaker['jk'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $pencaker['jk'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Domisili Wilayah <span
                                    class="text-danger">*</span></label>
                            <select name="domisili" id="selDomisili" class="form-select" required>
                                <option value="kab_pasuruan" <?= $pencaker['domisili'] === 'kab_pasuruan' ? 'selected' : '' ?>>Kabupaten Pasuruan</option>
                                <option value="luar" <?= $pencaker['domisili'] === 'luar' ? 'selected' : '' ?>>Luar
                                    Kabupaten Pasuruan (Kota Pasuruan / Daerah Lain)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. HP / WhatsApp <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="hp" value="<?= esc($pencaker['hp']) ?>" class="form-control"
                                required>
                        </div>

                        <!-- Jika Domisili Kab. Pasuruan -->
                        <div class="col-md-6 box-kab-pasuruan">
                            <label class="form-label small fw-semibold">Kecamatan (Kab. Pasuruan) <span
                                    class="text-danger">*</span></label>
                            <select id="selKecamatan" class="form-select">
                                <option value="">-- Pilih Kecamatan --</option>
                                <?php foreach ($kecamatan as $k): ?>
                                    <option value="<?= $k['id'] ?>" <?= ($pencaker['kecamatan_id'] ?? 0) == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 box-kab-pasuruan">
                            <label class="form-label small fw-semibold">Desa / Kelurahan <span
                                    class="text-danger">*</span></label>
                            <select name="desa_id" id="selDesa" class="form-select">
                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                <?php foreach ($desa as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= ($pencaker['desa_id'] ?? 0) == $d['id'] ? 'selected' : '' ?>><?= esc($d['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Jika Domisili Luar Kab. Pasuruan -->
                        <div class="col-12 box-luar-pasuruan" style="display:none;">
                            <label class="form-label small fw-semibold">Nama Kabupaten / Kota Domisili <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="kota_luar" value="<?= esc($pencaker['kota_luar']) ?>"
                                class="form-control" placeholder="Contoh: Kota Pasuruan, Kab. Sidoarjo, Kota Malang">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat Lengkap (Jalan / RT / RW / Dusun) <span
                                    class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2" class="form-control"
                                required><?= esc($pencaker['alamat']) ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tingkat Pendidikan Terakhir <span
                                    class="text-danger">*</span></label>
                            <select name="pendidikan" class="form-select" required>
                                <?php foreach (['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'D4/S1', 'S2'] as $p): ?>
                                    <option value="<?= $p ?>" <?= $pencaker['pendidikan'] === $p ? 'selected' : '' ?>><?= $p ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Jurusan / Program Studi <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="jurusan" value="<?= esc($pencaker['jurusan']) ?>"
                                class="form-control" placeholder="Contoh: Teknik Pemesinan / Administrasi Perkantoran"
                                required>
                        </div>

                        <div class="col-md-5">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="disabilitas" value="1"
                                    id="chkDisabilitas" <?= !empty($pencaker['disabilitas']) ? 'checked' : '' ?>>
                                <label class="form-check-label small fw-semibold" for="chkDisabilitas">Saya Penyandang
                                    Disabilitas</label>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold">Keterangan Jenis Disabilitas (Jika Ya)</label>
                            <input type="text" name="jenis_disabilitas"
                                value="<?= esc($pencaker['jenis_disabilitas']) ?>" class="form-control"
                                placeholder="Contoh: Disabilitas Daksa Ringan / Rungu">
                        </div>

                        <hr class="my-2">

                        <!-- Unggah Pas Foto (Maks 1 MB) -->
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Unggah Pas Foto Resmi (JPG/PNG, Maksimal 1
                                MB)</label>
                            <div class="d-flex align-items-center gap-3">
                                <?php if (!empty($pencaker['foto'])): ?>
                                    <img src="<?= base_url('media/foto/' . $pencaker['foto']) ?>" alt="Pas Foto"
                                        class="rounded border" style="width:70px;height:90px;object-fit:cover;">
                                <?php else: ?>
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small"
                                        style="width:70px;height:90px;">Foto</div>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <input type="file" name="foto" accept=".jpg,.jpeg,.png" class="form-control">
                                    <small class="text-muted">Satu-satunya file yang diunggah ke sistem adalah Pas Foto
                                        (maks. 1 MB). Digunakan pada Kartu Peserta ber-QR dan profil pelamar.</small>
                                </div>
                            </div>
                        </div>

                        <!-- 1 Link Berkas Gabungan PDF -->
                        <div class="col-12">
                            <div class="bg-primary bg-opacity-10 border border-primary-subtle rounded-3 p-3">
                                <label class="form-label fw-bold text-primary mb-1">
                                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Link Berkas Lamaran Gabungan (1 File
                                    PDF di Google Drive) <span class="text-danger">*</span>
                                </label>
                                <input type="url" name="link_berkas" value="<?= esc($pencaker['link_berkas']) ?>"
                                    class="form-control mb-2" placeholder="https://drive.google.com/file/d/..."
                                    required>
                                <div class="small text-dark">
                                    <strong>Petunjuk Wajib:</strong>
                                    <ol class="mb-0 ps-3">
                                        <li>Gabungkan seluruh dokumen lamaran Anda (<strong>CV, Ijazah Terakhir,
                                                Sertifikat Pelatihan, KTP, dan berkas pendukung lain</strong>) menjadi
                                            <strong>1 file PDF</strong>.</li>
                                        <li>Unggah file PDF tersebut ke <strong>Google Drive</strong> Anda.</li>
                                        <li>Klik kanan file di Google Drive &rarr; <strong>Bagikan (Share)</strong>
                                            &rarr; Ubah akses umum menjadi <strong>"Siapa saja yang memiliki link"
                                                (Anyone with the link)</strong>, lalu salin dan tempelkan link-nya pada
                                            kolom di atas.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Biodata & Link Berkas
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Sertifikat Pelatihan (Alumni BLK) & Pengalaman Kerja -->
    <div class="col-lg-4">
        <div class="card stat-card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-award text-warning me-1"></i> Keahlian / Sertifikat Pelatihan
                </h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('pencaker/keahlian/tambah') ?>" class="mb-3">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="text" name="nama" class="form-control form-control-sm"
                            placeholder="Nama Kejuruan / Sertifikat (mis: Las SMAW)" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-8">
                            <input type="text" name="penerbit" class="form-control form-control-sm"
                                placeholder="Lembaga Penerbit (mis: UPT BLK Pasuruan)" required>
                        </div>
                        <div class="col-4">
                            <input type="number" name="tahun" value="<?= date('Y') ?>"
                                class="form-control form-control-sm" placeholder="Tahun" required>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="alumni_blk" value="1" id="chkAlumniBlk">
                        <label class="form-check-label small fw-semibold text-primary" for="chkAlumniBlk">Saya Alumni
                            Pelatihan UPT BLK Pasuruan</label>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-plus-lg"></i>
                        Tambah Keahlian</button>
                </form>

                <ul class="list-group list-group-flush small">
                    <?php foreach ($keahlian as $k): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold"><?= esc($k['nama']) ?></div>
                                <div class="text-muted"><?= esc($k['penerbit']) ?> (<?= esc($k['tahun']) ?>)</div>
                                <?php if ($k['alumni_blk']): ?>
                                    <span class="badge bg-primary-subtle text-primary">Alumni BLK</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" action="<?= base_url('pencaker/keahlian/hapus/' . $k['id']) ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm text-danger p-0"><i
                                        class="bi bi-trash"></i></button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-briefcase text-success me-1"></i> Pengalaman Kerja</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('pencaker/pengalaman/tambah') ?>" class="mb-3">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="text" name="nama_perusahaan" class="form-control form-control-sm"
                            placeholder="Nama Perusahaan" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="jabatan" class="form-control form-control-sm"
                            placeholder="Jabatan / Posisi" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="number" name="tahun_mulai" value="<?= date('Y') - 1 ?>"
                                class="form-control form-control-sm" placeholder="Thn Mulai" required>
                        </div>
                        <div class="col-6">
                            <input type="text" name="tahun_selesai" value="Sekarang"
                                class="form-control form-control-sm" placeholder="Thn Selesai" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-success w-100"><i class="bi bi-plus-lg"></i>
                        Tambah Pengalaman</button>
                </form>

                <ul class="list-group list-group-flush small">
                    <?php foreach ($pengalaman as $pg): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold"><?= esc($pg['jabatan']) ?></div>
                                <div class="text-muted"><?= esc($pg['nama_perusahaan']) ?> (<?= esc($pg['tahun_mulai']) ?> -
                                    <?= esc($pg['tahun_selesai']) ?>)</div>
                            </div>
                            <form method="post" action="<?= base_url('pencaker/pengalaman/hapus/' . $pg['id']) ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm text-danger p-0"><i
                                        class="bi bi-trash"></i></button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleDomisili() {
        const val = $('#selDomisili').val();
        if (val === 'kab_pasuruan') {
            $('.box-kab-pasuruan').show();
            $('.box-luar-pasuruan').hide();
        } else {
            $('.box-kab-pasuruan').hide();
            $('.box-luar-pasuruan').show();
        }
    }
    $('#selDomisili').on('change', toggleDomisili);
    toggleDomisili();

    $('#selKecamatan').on('change', function () {
        const kecId = $(this).val();
        const $desa = $('#selDesa');
        $desa.html('<option value="">Memuat desa...</option>');
        if (!kecId) {
            $desa.html('<option value="">-- Pilih Desa/Kelurahan --</option>');
            return;
        }
        $.getJSON('<?= base_url('api/desa') ?>/' + kecId, function (data) {
            let html = '<option value="">-- Pilih Desa/Kelurahan --</option>';
            data.forEach(function (d) {
                html += `<option value="${d.id}">${d.nama}</option>`;
            });
            $desa.html(html);
        });
    });
</script>
<?= $this->endSection() ?>