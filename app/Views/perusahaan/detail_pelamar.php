<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card stat-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Profil Lengkap Pelamar</h6>
                <?php if (! empty($pelamar['link_berkas'])): ?>
                    <a href="<?= esc($pelamar['link_berkas']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-danger btn-sm fw-semibold">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Berkas Gabungan PDF (Google Drive)
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <div class="d-flex gap-3 mb-4">
                    <?php if (! empty($pelamar['foto'])): ?>
                        <img src="<?= base_url('media/foto/' . $pelamar['foto']) ?>" class="rounded border" style="width:90px;height:115px;object-fit:cover;">
                    <?php else: ?>
                        <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small" style="width:90px;height:115px;">Foto</div>
                    <?php endif; ?>
                    <div>
                        <h4 class="fw-bold mb-1"><?= esc($pelamar['nama']) ?></h4>
                        <div class="text-primary fw-semibold mb-1">Melamar Jabatan: <?= esc($pelamar['jabatan']) ?></div>
                        <div class="small text-muted">NIK: <?= esc($pelamar['nik']) ?> &bull; Gender: <?= $pelamar['jk'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                        <div class="small text-muted">TTL: <?= esc($pelamar['tempat_lahir']) ?>, <?= esc($pelamar['tgl_lahir']) ?></div>
                        <div class="small text-muted">HP/WA: <?= esc($pelamar['hp']) ?> &bull; Email: <?= esc($pelamar['email']) ?></div>
                    </div>
                </div>

                <div class="row g-3 small bg-light rounded-3 p-3 mb-3">
                    <div class="col-md-6">
                        <span class="text-muted d-block">Pendidikan & Jurusan</span>
                        <strong><?= esc($pelamar['pendidikan']) ?> — <?= esc($pelamar['jurusan']) ?></strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block">Domisili</span>
                        <strong><?= $pelamar['domisili'] === 'kab_pasuruan' ? esc($pelamar['nama_desa'] . ', Kec. ' . $pelamar['nama_kecamatan'] . ', Kab. Pasuruan') : esc($pelamar['kota_luar']) ?></strong>
                    </div>
                    <div class="col-md-12">
                        <span class="text-muted d-block">Alamat Lengkap</span>
                        <strong><?= esc($pelamar['alamat']) ?></strong>
                    </div>
                    <div class="col-md-12">
                        <span class="text-muted d-block">Link Berkas PDF Gabungan (CV, Ijazah, Sertifikat)</span>
                        <a href="<?= esc($pelamar['link_berkas']) ?>" target="_blank" rel="noopener noreferrer" class="fw-bold"><?= esc($pelamar['link_berkas']) ?></a>
                    </div>
                </div>

                <h6 class="fw-bold mt-4 mb-2">Keahlian & Sertifikat Pelatihan</h6>
                <ul class="list-group list-group-flush small mb-3">
                    <?php if (empty($keahlian)): ?>
                        <li class="list-group-item px-0 text-muted">Tidak ada data keahlian tambahan.</li>
                    <?php endif; ?>
                    <?php foreach ($keahlian as $k): ?>
                        <li class="list-group-item px-0">
                            <strong><?= esc($k['nama']) ?></strong> — <?= esc($k['penerbit']) ?> (<?= esc($k['tahun']) ?>)
                            <?php if ($k['alumni_blk']): ?>
                                <span class="badge bg-primary ms-1">Alumni BLK Pasuruan</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h6 class="fw-bold mt-3 mb-2">Pengalaman Kerja</h6>
                <ul class="list-group list-group-flush small">
                    <?php if (empty($pengalaman)): ?>
                        <li class="list-group-item px-0 text-muted">Fresh Graduate / Belum ada pengalaman kerja.</li>
                    <?php endif; ?>
                    <?php foreach ($pengalaman as $pg): ?>
                        <li class="list-group-item px-0">
                            <strong><?= esc($pg['jabatan']) ?></strong> di <?= esc($pg['nama_perusahaan']) ?> (<?= esc($pg['tahun_mulai']) ?> - <?= esc($pg['tahun_selesai']) ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card stat-card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Ubah Status Seleksi & Panggilan Tes</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('perusahaan/pelamar/status') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="lamaran_id" value="<?= $lamaranId ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Lamaran</label>
                        <select name="status" class="form-select" <?= $seleksiClosed ? 'disabled' : '' ?>>
                            <?php foreach (['Dikirim','LolosBerkas','PanggilTes','Diterima','Ditolak'] as $st): ?>
                                <option value="<?= $st ?>" <?= $pelamar['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jadwal Tes / Interview (Jika Dipanggil Tes)</label>
                        <input type="datetime-local" name="jadwal_tes" value="<?= $pelamar['jadwal_tes'] ? date('Y-m-d\TH:i', strtotime($pelamar['jadwal_tes'])) : '' ?>" class="form-control" <?= $seleksiClosed ? 'disabled' : '' ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Lokasi Pelaksanaan Tes / Interview</label>
                        <input type="text" name="lokasi_tes" value="<?= esc($pelamar['lokasi_tes']) ?>" class="form-control" placeholder="Contoh: Ruang HRD PT ... / Link Zoom" <?= $seleksiClosed ? 'disabled' : '' ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan / Pesan untuk Pelamar (Dikirim via Email)</label>
                        <textarea name="catatan" rows="3" class="form-control" <?= $seleksiClosed ? 'disabled' : '' ?>><?= esc($pelamar['catatan']) ?></textarea>
                    </div>
                    <?php
                        $rawHp = preg_replace('/\D+/', '', (string) ($pelamar['hp'] ?? ''));
                        if (str_starts_with($rawHp, '0')) {
                            $waNumber = '62' . substr($rawHp, 1);
                        } elseif (str_starts_with($rawHp, '8')) {
                            $waNumber = '62' . $rawHp;
                        } else {
                            $waNumber = $rawHp;
                        }

                        $namaPerusahaan = $perusahaan['nama'] ?? session()->get('nama') ?? 'Perusahaan';
                        $alamatDefault  = $perusahaan['alamat'] ?? '-';

                        $waLines = [
                            "Halo, *{$pelamar['nama']}*",
                            "",
                            "Status lamaran Anda untuk posisi *{$pelamar['jabatan']}* di *{$namaPerusahaan}* telah diperbarui menjadi: *{$pelamar['status']}*.",
                        ];
                        if ($pelamar['status'] === 'PanggilTes' && ! empty($pelamar['jadwal_tes'])) {
                            $waLines[] = "";
                            $waLines[] = "*Jadwal Tes / Interview:* " . date('d M Y H:i', strtotime($pelamar['jadwal_tes'])) . " WIB";
                            $waLines[] = "*Lokasi Tes:* " . ($pelamar['lokasi_tes'] ?: $alamatDefault);
                        }
                        $waLines[] = "";
                        $waLines[] = "*Catatan HRD:* " . ($pelamar['catatan'] ?: '-');
                        $waLines[] = "";
                        $waLines[] = "Silakan login ke dashboard Job Market Fair UPT BLK Pasuruan untuk melihat detail lengkapnya.";

                        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode(implode("\n", $waLines));
                    ?>
                    <?php if (! $seleksiClosed): ?>
                        <div class="d-flex gap-2">
                            <button type="submit" name="notif_via" value="email" class="btn btn-primary w-50 fw-semibold">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Status & Kirim Notifikasi Email
                            </button>
                            <button type="submit" name="notif_via" value="wa" id="btnWaNotif" data-wa-url="<?= esc($waUrl) ?>" class="btn btn-success w-50 fw-semibold">
                                <i class="bi bi-whatsapp me-1"></i> Simpan Status & Kirim Notifikasi WhatsApp
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Riwayat Perubahan Status</h6>
            </div>
            <div class="card-body small">
                <ul class="list-group list-group-flush">
                    <?php foreach ($logs as $lg): ?>
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <strong><?= esc($lg['status_lama'] ?: 'Awal') ?> &rarr; <?= esc($lg['status_baru']) ?></strong>
                                <span class="text-muted"><?= esc($lg['created_at']) ?></span>
                            </div>
                            <div class="text-muted"><?= esc($lg['catatan']) ?></div>
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
    $(function() {
        const waNumber       = <?= json_encode($waNumber) ?>;
        const namaPelamar    = <?= json_encode($pelamar['nama'] ?? '') ?>;
        const jabatan        = <?= json_encode($pelamar['jabatan'] ?? '') ?>;
        const namaPerusahaan = <?= json_encode($namaPerusahaan) ?>;
        const alamatDefault  = <?= json_encode($alamatDefault) ?>;

        function formatJadwal(val) {
            if (!val) return '';
            const d = new Date(val);
            if (isNaN(d.getTime())) return val;
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = String(d.getDate()).padStart(2, '0');
            const month = months[d.getMonth()];
            const year = d.getFullYear();
            const hours = String(d.getHours()).padStart(2, '0');
            const mins = String(d.getMinutes()).padStart(2, '0');
            return `${day} ${month} ${year} ${hours}:${mins}`;
        }

        function buildWaUrl() {
            const status    = $('select[name="status"]').val() || '';
            const jadwal    = $('input[name="jadwal_tes"]').val() || '';
            const lokasi    = ($('input[name="lokasi_tes"]').val() || '').trim();
            const catatan   = ($('textarea[name="catatan"]').val() || '').trim();

            const lines = [
                `Halo, *${namaPelamar}*`,
                ``,
                `Status lamaran Anda untuk posisi *${jabatan}* di *${namaPerusahaan}* telah diperbarui menjadi: *${status}*.`
            ];

            if (status === 'PanggilTes' && jadwal) {
                lines.push(``);
                lines.push(`*Jadwal Tes / Interview:* ${formatJadwal(jadwal)} WIB`);
                lines.push(`*Lokasi Tes:* ${lokasi || alamatDefault}`);
            }

            lines.push(``);
            lines.push(`*Catatan HRD:* ${catatan || '-'}`);
            lines.push(``);
            lines.push(`Silakan login ke dashboard Job Market Fair UPT BLK Pasuruan untuk melihat detail lengkapnya.`);

            return `https://wa.me/${waNumber}?text=${encodeURIComponent(lines.join('\n'))}`;
        }

        $('#btnWaNotif').on('click', function() {
            window.open(buildWaUrl(), '_blank', 'noopener,noreferrer');
        });
    });
</script>
<?= $this->endSection() ?>
