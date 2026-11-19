<?php

namespace App\Controllers\Pencaker;

use App\Controllers\BaseController;
use App\Libraries\JmfService;

class Panel extends BaseController
{
    private function getPencaker(): array
    {
        $userId = (int) session()->get('user_id');
        $p = $this->db->table('pencaker p')
            ->select('p.*, d.nama AS nama_desa, d.kecamatan_id, k.nama AS nama_kecamatan')
            ->join('desa d', 'd.id = p.desa_id', 'left')
            ->join('kecamatan k', 'k.id = d.kecamatan_id', 'left')
            ->where('p.user_id', $userId)
            ->get()
            ->getRowArray();

        return $p ?: [];
    }

    public function index()
    {
        $p = $this->getPencaker();
        $event = $this->activeEvent;
        $kepesertaan = null;
        $sesiList = [];
        $qrSvg = null;

        if ($event && !empty($p['id'])) {
            $kepesertaan = $this->db->table('event_peserta ep')
                ->select('ep.*, s.nama AS nama_sesi, s.tanggal AS tgl_sesi, s.jam_mulai, s.jam_selesai')
                ->join('event_sesi s', 's.id = ep.sesi_id', 'left')
                ->where('ep.event_id', $event['id'])
                ->where('ep.pencaker_id', $p['id'])
                ->get()
                ->getRowArray();

            if ($kepesertaan) {
                $qrSvg = JmfService::generateQrDataUri($kepesertaan['qr_token']);
            }

            if (!empty($event['sesi_aktif'])) {
                $sesiList = $this->db->query("
                    SELECT s.*, COUNT(ep.id) AS terisi
                    FROM event_sesi s
                    LEFT JOIN event_peserta ep ON ep.sesi_id = s.id
                    WHERE s.event_id = ?
                    GROUP BY s.id
                    ORDER BY s.urutan ASC, s.jam_mulai ASC
                ", [$event['id']])->getResultArray();
            }
        }

        $jmlLamaran = 0;
        if ($kepesertaan) {
            $jmlLamaran = (int) $this->db->table('lamaran')->where('event_peserta_id', $kepesertaan['id'])->countAllResults();
        }

        return $this->renderView('pencaker/dashboard', [
            'title' => 'Dashboard Pencari Kerja',
            'pencaker' => $p,
            'kepesertaan' => $kepesertaan,
            'sesiList' => $sesiList,
            'qrSvg' => $qrSvg,
            'jmlLamaran' => $jmlLamaran,
        ]);
    }

    public function profil()
    {
        $p = $this->getPencaker();
        $kecamatan = $this->db->table('kecamatan')->orderBy('nama', 'ASC')->get()->getResultArray();
        $desa = [];
        if (!empty($p['kecamatan_id'])) {
            $desa = $this->db->table('desa')->where('kecamatan_id', $p['kecamatan_id'])->orderBy('nama', 'ASC')->get()->getResultArray();
        }

        $pengalaman = $this->db->table('pengalaman_kerja')->where('pencaker_id', $p['id'])->orderBy('tahun_mulai', 'DESC')->get()->getResultArray();
        $keahlian = $this->db->table('keahlian')->where('pencaker_id', $p['id'])->orderBy('tahun', 'DESC')->get()->getResultArray();

        return $this->renderView('pencaker/profil', [
            'title' => 'Profil, Pas Foto & Link Berkas',
            'pencaker' => $p,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
            'pengalaman' => $pengalaman,
            'keahlian' => $keahlian,
        ]);
    }

    public function saveProfil()
    {
        $p = $this->getPencaker();
        $linkBerkas = trim((string) $this->request->getPost('link_berkas'));

        if ($linkBerkas === '' || !str_starts_with(strtolower($linkBerkas), 'https://')) {
            return redirect()->back()->withInput()->with('error', 'Link berkas gabungan PDF wajib diisi dan harus diawali dengan https:// (mis. link Google Drive).');
        }

        $domisili = $this->request->getPost('domisili') === 'luar' ? 'luar' : 'kab_pasuruan';
        $desaId = $domisili === 'kab_pasuruan' ? ((int) $this->request->getPost('desa_id') ?: null) : null;
        $kotaLuar = $domisili === 'luar' ? trim((string) $this->request->getPost('kota_luar')) : null;

        if ($domisili === 'kab_pasuruan' && !$desaId) {
            return redirect()->back()->withInput()->with('error', 'Silakan pilih Kecamatan dan Desa/Kelurahan domisili Anda di Kabupaten Pasuruan.');
        }
        if ($domisili === 'luar' && empty($kotaLuar)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi nama Kabupaten/Kota domisili Anda.');
        }

        $fotoFilename = $p['foto'];
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $validRules = [
                'foto' => [
                    'rules' => 'uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,1024]',
                    'errors' => [
                        'max_size' => 'Ukuran pas foto maksimal 1 MB (1024 KB).',
                        'mime_in' => 'Pas foto harus berformat JPG atau PNG.',
                    ],
                ],
            ];
            if (!$this->validate($validRules)) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $newName = $fileFoto->getRandomName();
            $uploadDir = WRITEPATH . 'uploads/foto';
            $fileFoto->move($uploadDir, $newName);

            // Resize to max 400x600 to save hosting storage
            try {
                \Config\Services::image()
                    ->withFile($uploadDir . '/' . $newName)
                    ->resize(400, 600, true, 'height')
                    ->save($uploadDir . '/' . $newName, 85);
            } catch (\Throwable $e) {
                // Keep original if resize fails
            }

            if (!empty($fotoFilename) && is_file($uploadDir . '/' . $fotoFilename)) {
                @unlink($uploadDir . '/' . $fotoFilename);
            }
            $fotoFilename = $newName;
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $tempatLahir = trim((string) $this->request->getPost('tempat_lahir'));
        $tglLahir = trim((string) $this->request->getPost('tgl_lahir'));
        $alamat = trim((string) $this->request->getPost('alamat'));
        $hp = trim((string) $this->request->getPost('hp'));
        $pendidikan = (string) $this->request->getPost('pendidikan');
        $jurusan = trim((string) $this->request->getPost('jurusan'));
        $disabilitas = $this->request->getPost('disabilitas') ? 1 : 0;
        $jenisDis = $disabilitas ? trim((string) $this->request->getPost('jenis_disabilitas')) : null;

        // Check completeness (basic data + link_berkas)
        $lengkap = (!empty($nama) && !empty($tempatLahir) && !empty($tglLahir) && !empty($alamat) && !empty($hp) && !empty($linkBerkas)) ? 1 : 0;

        $this->db->table('pencaker')->where('id', $p['id'])->update([
            'nama' => $nama,
            'tempat_lahir' => $tempatLahir,
            'tgl_lahir' => $tglLahir ?: null,
            'jk' => $this->request->getPost('jk') === 'P' ? 'P' : 'L',
            'alamat' => $alamat,
            'domisili' => $domisili,
            'desa_id' => $desaId,
            'kota_luar' => $kotaLuar,
            'hp' => $hp,
            'pendidikan' => $pendidikan,
            'jurusan' => $jurusan,
            'disabilitas' => $disabilitas,
            'jenis_disabilitas' => $jenisDis,
            'foto' => $fotoFilename,
            'link_berkas' => $linkBerkas,
            'profil_lengkap' => $lengkap,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $email = trim((string) $this->request->getPost('email'));
        if ($email !== '') {
            $exists = $this->db->table('users')
                ->where('email', $email)
                ->where('id !=', $p['user_id'])
                ->countAllResults();
            if ($exists > 0) {
                return redirect()->back()->withInput()->with('error', 'Email tersebut sudah digunakan oleh akun lain.');
            }
        }

        $userUpdate = [
            'nama'       => $nama,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($email !== '') {
            $userUpdate['email'] = $email;
            session()->set('email', $email);
        }
        $passwordBaru = trim((string) $this->request->getPost('password_baru'));
        if ($passwordBaru !== '') {
            $userUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        $this->db->table('users')->where('id', $p['user_id'])->update($userUpdate);
        session()->set('nama', $nama);

        return redirect()->to('/pencaker/profil')->with('success', 'Data profil dan link berkas berhasil disimpan.');
    }

    public function addPengalaman()
    {
        $p = $this->getPencaker();
        $this->db->table('pengalaman_kerja')->insert([
            'pencaker_id' => $p['id'],
            'nama_perusahaan' => trim((string) $this->request->getPost('nama_perusahaan')),
            'jabatan' => trim((string) $this->request->getPost('jabatan')),
            'tahun_mulai' => (int) $this->request->getPost('tahun_mulai'),
            'tahun_selesai' => trim((string) $this->request->getPost('tahun_selesai')) ?: 'Sekarang',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/pencaker/profil')->with('success', 'Riwayat pengalaman kerja ditambahkan.');
    }

    public function deletePengalaman(int $id)
    {
        $p = $this->getPencaker();
        $this->db->table('pengalaman_kerja')->where('id', $id)->where('pencaker_id', $p['id'])->delete();
        return redirect()->to('/pencaker/profil')->with('success', 'Pengalaman kerja dihapus.');
    }

    public function addKeahlian()
    {
        $p = $this->getPencaker();
        $this->db->table('keahlian')->insert([
            'pencaker_id' => $p['id'],
            'nama' => trim((string) $this->request->getPost('nama')),
            'penerbit' => trim((string) $this->request->getPost('penerbit')),
            'tahun' => (int) $this->request->getPost('tahun'),
            'alumni_blk' => $this->request->getPost('alumni_blk') ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/pencaker/profil')->with('success', 'Data sertifikat/pelatihan ditambahkan.');
    }

    public function deleteKeahlian(int $id)
    {
        $p = $this->getPencaker();
        $this->db->table('keahlian')->where('id', $id)->where('pencaker_id', $p['id'])->delete();
        return redirect()->to('/pencaker/profil')->with('success', 'Data sertifikat/pelatihan dihapus.');
    }

    public function daftarEvent()
    {
        $p = $this->getPencaker();
        $event = $this->activeEvent;

        if (!$event || empty($event['pendaftaran_dibuka'])) {
            return redirect()->to('/pencaker')->with('error', 'Pendaftaran event saat ini sedang ditutup.');
        }

        if (empty($p['profil_lengkap']) || empty($p['link_berkas'])) {
            return redirect()->to('/pencaker/profil')->with('error', 'Silakan lengkapi profil dan link berkas gabungan PDF terlebih dahulu sebelum mendaftar event.');
        }

        $exists = $this->db->table('event_peserta')
            ->where('event_id', $event['id'])
            ->where('pencaker_id', $p['id'])
            ->get()
            ->getRowArray();

        if ($exists) {
            return redirect()->to('/pencaker')->with('error', 'Anda sudah terdaftar pada event ini.');
        }

        $sesiId = null;
        $sesiInfo = null;

        $this->db->transBegin();

        if (!empty($event['sesi_aktif'])) {
            $sesiId = (int) $this->request->getPost('sesi_id');
            // Lock session row to prevent race conditions exceeding quota
            $sesiRow = $this->db->query("SELECT * FROM event_sesi WHERE id = ? AND event_id = ? FOR UPDATE", [$sesiId, $event['id']])->getRowArray();
            if (!$sesiRow) {
                $this->db->transRollback();
                return redirect()->to('/pencaker')->with('error', 'Silakan pilih sesi kedatangan yang valid.');
            }

            $terisi = (int) $this->db->query("SELECT COUNT(id) AS jml FROM event_peserta WHERE sesi_id = ?", [$sesiId])->getRow('jml');
            if ($terisi >= (int) $sesiRow['kuota']) {
                $this->db->transRollback();
                return redirect()->to('/pencaker')->with('error', 'Maaf, kuota untuk ' . $sesiRow['nama'] . ' sudah penuh. Silakan pilih sesi lain.');
            }
            $sesiInfo = $sesiRow;
        }

        $now = date('Y-m-d H:i:s');
        $noReg = 'JMF' . $event['tahun'] . '-' . str_pad((string) $p['id'], 4, '0', STR_PAD_LEFT);
        $qrToken = 'PESERTA-' . $event['tahun'] . '-' . strtoupper(bin2hex(random_bytes(5)));

        $this->db->table('event_peserta')->insert([
            'event_id' => $event['id'],
            'pencaker_id' => $p['id'],
            'sesi_id' => $sesiId,
            'no_registrasi' => $noReg,
            'qr_token' => $qrToken,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->db->transCommit();

        // Queue confirmation email
        $sesiText = $sesiInfo
            ? "<p><strong>Sesi Kedatangan:</strong> {$sesiInfo['nama']} ({$sesiInfo['tanggal']} pukul " . substr($sesiInfo['jam_mulai'], 0, 5) . " - " . substr($sesiInfo['jam_selesai'], 0, 5) . " WIB)</p>"
            : "";

        JmfService::queueEmail(
            session()->get('email'),
            "Bukti Registrasi {$event['nama']} - {$noReg}",
            "<h3>Halo, {$p['nama']}</h3>
             <p>Anda telah terdaftar sebagai peserta <strong>{$event['nama']}</strong>.</p>
             <p><strong>Nomor Registrasi:</strong> {$noReg}<br>
             <strong>Kode QR Check-in:</strong> {$qrToken}</p>
             {$sesiText}
             <p>Silakan unduh Kartu Peserta ber-QR di dashboard akun Anda dan tunjukkan kepada petugas registrasi saat hadir di UPT BLK Pasuruan.</p>"
        );

        return redirect()->to('/pencaker')->with('success', 'Berhasil terdaftar pada event! Kartu Peserta ber-QR Anda sudah siap diunduh.');
    }

    public function pindahSesi()
    {
        $p = $this->getPencaker();
        $event = $this->activeEvent;
        if (!$event || empty($event['sesi_aktif']) || empty($event['pendaftaran_dibuka'])) {
            return redirect()->to('/pencaker')->with('error', 'Perubahan sesi kedatangan tidak tersedia.');
        }

        $newSesiId = (int) $this->request->getPost('sesi_id');

        $this->db->transBegin();

        $peserta = $this->db->query("SELECT * FROM event_peserta WHERE event_id = ? AND pencaker_id = ? FOR UPDATE", [$event['id'], $p['id']])->getRowArray();
        if (!$peserta || !empty($peserta['checkin_at'])) {
            $this->db->transRollback();
            return redirect()->to('/pencaker')->with('error', 'Sesi tidak dapat diubah karena Anda sudah melakukan check-in.');
        }

        $sesiRow = $this->db->query("SELECT * FROM event_sesi WHERE id = ? AND event_id = ? FOR UPDATE", [$newSesiId, $event['id']])->getRowArray();
        if (!$sesiRow) {
            $this->db->transRollback();
            return redirect()->to('/pencaker')->with('error', 'Sesi tujuan tidak valid.');
        }

        $terisi = (int) $this->db->query("SELECT COUNT(id) AS jml FROM event_peserta WHERE sesi_id = ?", [$newSesiId])->getRow('jml');
        if ($terisi >= (int) $sesiRow['kuota']) {
            $this->db->transRollback();
            return redirect()->to('/pencaker')->with('error', 'Kuota ' . $sesiRow['nama'] . ' sudah penuh.');
        }

        $this->db->table('event_peserta')->where('id', $peserta['id'])->update([
            'sesi_id' => $newSesiId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->transCommit();

        return redirect()->to('/pencaker')->with('success', 'Berhasil pindah ke ' . $sesiRow['nama'] . '.');
    }

    public function kartuQr()
    {
        $p = $this->getPencaker();
        $event = $this->activeEvent;

        $kepesertaan = $this->db->table('event_peserta ep')
            ->select('ep.*, s.nama AS nama_sesi, s.tanggal AS tgl_sesi, s.jam_mulai, s.jam_selesai')
            ->join('event_sesi s', 's.id = ep.sesi_id', 'left')
            ->where('ep.event_id', $event['id'] ?? 0)
            ->where('ep.pencaker_id', $p['id'] ?? 0)
            ->get()
            ->getRowArray();

        if (!$kepesertaan) {
            return redirect()->to('/pencaker')->with('error', 'Anda belum terdaftar pada event aktif.');
        }

        $qrDataUri = JmfService::generateQrDataUri($kepesertaan['qr_token']);
        $fotoBase64 = null;
        if (!empty($p['foto'])) {
            $fotoPath = WRITEPATH . 'uploads/foto/' . basename($p['foto']);
            if (is_file($fotoPath)) {
                $mime = mime_content_type($fotoPath) ?: 'image/jpeg';
                $fotoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fotoPath));
            }
        }

        $html = view('pdf/kartu_peserta', [
            'pengaturan' => $this->pengaturan,
            'event' => $event,
            'pencaker' => $p,
            'kepesertaan' => $kepesertaan,
            'qrDataUri' => $qrDataUri,
            'fotoBase64' => $fotoBase64,
        ]);

        return JmfService::renderPdf($html, 'Kartu-Peserta-' . $kepesertaan['no_registrasi'] . '.pdf', 'A5', 'portrait');
    }

    public function scanBooth()
    {
        $boothList = [];
        if ($this->activeEvent) {
            $boothList = $this->db->table('event_perusahaan ep')
                ->select('ep.*, p.nama AS nama_perusahaan, p.bidang_usaha')
                ->join('perusahaan p', 'p.id = ep.perusahaan_id')
                ->join('users u', 'u.id = p.user_id')
                ->where('ep.event_id', $this->activeEvent['id'])
                ->where('u.is_active', 1)
                ->orderBy('ep.no_booth', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $this->renderView('pencaker/scan_booth', [
            'title' => 'Scan QR Booth Perusahaan',
            'boothList' => $boothList,
        ]);
    }

    public function lamarBooth(string $token)
    {
        $p = $this->getPencaker();
        $event = $this->activeEvent;

        $ep = $this->db->table('event_perusahaan ep')
            ->select('ep.*, p.nama AS nama_perusahaan, p.bidang_usaha, p.alamat, p.deskripsi, e.nama AS nama_event, e.lamaran_dibuka')
            ->join('perusahaan p', 'p.id = ep.perusahaan_id')
            ->join('users u', 'u.id = p.user_id')
            ->join('events e', 'e.id = ep.event_id')
            ->where('ep.qr_token', trim($token))
            ->where('u.is_active', 1)
            ->get()
            ->getRowArray();

        if (!$ep) {
            return redirect()->to('/pencaker/scan-booth')->with('error', 'Kode QR Booth tidak ditemukan atau tidak valid.');
        }

        $kepesertaan = $this->db->table('event_peserta')
            ->where('event_id', $ep['event_id'])
            ->where('pencaker_id', $p['id'] ?? 0)
            ->get()
            ->getRowArray();

        $existingLamaran = null;
        if ($kepesertaan) {
            $existingLamaran = $this->db->table('lamaran l')
                ->select('l.*, low.jabatan')
                ->join('lowongan low', 'low.id = l.lowongan_id')
                ->where('l.event_peserta_id', $kepesertaan['id'])
                ->where('l.event_perusahaan_id', $ep['id'])
                ->get()
                ->getRowArray();
        }

        $lowonganList = $this->db->table('lowongan')
            ->where('event_perusahaan_id', $ep['id'])
            ->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        return $this->renderView('pencaker/lamar_booth', [
            'title' => 'Lamar di Booth ' . $ep['no_booth'] . ' - ' . $ep['nama_perusahaan'],
            'pencaker' => $p,
            'ep' => $ep,
            'kepesertaan' => $kepesertaan,
            'existingLamaran' => $existingLamaran,
            'lowonganList' => $lowonganList,
        ]);
    }

    public function submitLamarBooth(string $token)
    {
        $p = $this->getPencaker();
        $ep = $this->db->table('event_perusahaan ep')
            ->select('ep.*, p.nama AS nama_perusahaan, e.lamaran_dibuka')
            ->join('perusahaan p', 'p.id = ep.perusahaan_id')
            ->join('users u', 'u.id = p.user_id')
            ->join('events e', 'e.id = ep.event_id')
            ->where('ep.qr_token', trim($token))
            ->where('u.is_active', 1)
            ->get()
            ->getRowArray();

        if (!$ep || empty($ep['lamaran_dibuka'])) {
            return redirect()->back()->with('error', 'Pengiriman lamaran sedang ditutup oleh panitia.');
        }

        if (empty($p['profil_lengkap']) || empty($p['link_berkas'])) {
            return redirect()->to('/pencaker/profil')->with('error', 'Profil dan Link Berkas Gabungan PDF Anda belum lengkap.');
        }

        $kepesertaan = $this->db->table('event_peserta')
            ->where('event_id', $ep['event_id'])
            ->where('pencaker_id', $p['id'])
            ->get()
            ->getRowArray();

        if (!$kepesertaan) {
            return redirect()->to('/pencaker')->with('error', 'Anda belum terdaftar pada event ini.');
        }

        if (empty($kepesertaan['checkin_at'])) {
            return redirect()->back()->with('error', 'Anda belum melakukan Check-in kehadiran di Meja Registrasi. Silakan tunjukkan Kartu QR Anda kepada petugas meja depan terlebih dahulu.');
        }

        // Enforce 1 vacancy per company rule
        $already = $this->db->table('lamaran')
            ->where('event_peserta_id', $kepesertaan['id'])
            ->where('event_perusahaan_id', $ep['id'])
            ->countAllResults();

        if ($already > 0) {
            return redirect()->back()->with('error', 'Anda sudah melamar pada perusahaan ini. Setiap peserta dibatasi maksimal 1 lowongan per perusahaan.');
        }

        $lowonganId = (int) $this->request->getPost('lowongan_id');
        $low = $this->db->table('lowongan')
            ->where('id', $lowonganId)
            ->where('event_perusahaan_id', $ep['id'])
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if (!$low) {
            return redirect()->back()->with('error', 'Silakan pilih lowongan jabatan yang valid.');
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();

        $this->db->table('lamaran')->insert([
            'event_peserta_id' => $kepesertaan['id'],
            'lowongan_id' => $low['id'],
            'event_perusahaan_id' => $ep['id'],
            'status' => 'Dikirim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $lamaranId = $this->db->insertID();

        $this->db->table('lamaran_log')->insert([
            'lamaran_id' => $lamaranId,
            'status_lama' => null,
            'status_baru' => 'Dikirim',
            'catatan' => 'Melamar melalui scan QR Booth ' . $ep['no_booth'],
            'user_id' => session()->get('user_id'),
            'created_at' => $now,
        ]);

        $this->db->transComplete();

        return redirect()->to('/pencaker/lamaran')->with('success', 'Lamaran Anda untuk posisi "' . $low['jabatan'] . '" di ' . $ep['nama_perusahaan'] . ' berhasil dikirim!');
    }

    public function lamaran()
    {
        $p = $this->getPencaker();
        $list = $this->db->query("
            SELECT l.*, low.jabatan, low.lokasi_kerja, pr.nama AS nama_perusahaan, ep.no_booth, ev.nama AS nama_event, ev.tahun
            FROM lamaran l
            JOIN event_peserta eps ON eps.id = l.event_peserta_id
            JOIN lowongan low ON low.id = l.lowongan_id
            JOIN event_perusahaan ep ON ep.id = l.event_perusahaan_id
            JOIN perusahaan pr ON pr.id = ep.perusahaan_id
            JOIN events ev ON ev.id = eps.event_id
            WHERE eps.pencaker_id = ?
            ORDER BY l.id DESC
        ", [$p['id'] ?? 0])->getResultArray();

        return $this->renderView('pencaker/lamaran', [
            'title' => 'Riwayat & Status Lamaran Saya',
            'lamaran' => $list,
        ]);
    }

    public function survei()
    {
        $event = $this->activeEvent;
        $pertanyaan = [];
        $jawabanMap = [];
        if ($event) {
            $pertanyaan = $this->db->table('survei_pertanyaan')
                ->where('event_id', $event['id'])
                ->where('target', 'pencaker')
                ->orderBy('urutan', 'ASC')
                ->get()
                ->getResultArray();

            $jRows = $this->db->table('survei_jawaban')
                ->where('user_id', session()->get('user_id'))
                ->get()
                ->getResultArray();
            foreach ($jRows as $j) {
                $jawabanMap[$j['pertanyaan_id']] = $j;
            }
        }

        return $this->renderView('pencaker/survei', [
            'title' => 'Survei Kepuasan Peserta Job Market Fair',
            'pertanyaan' => $pertanyaan,
            'jawabanMap' => $jawabanMap,
        ]);
    }

    public function saveSurvei()
    {
        $event = $this->activeEvent;
        if (!$event) {
            return redirect()->back();
        }
        $userId = (int) session()->get('user_id');
        $pertanyaan = $this->db->table('survei_pertanyaan')
            ->where('event_id', $event['id'])
            ->where('target', 'pencaker')
            ->get()
            ->getResultArray();

        $now = date('Y-m-d H:i:s');
        foreach ($pertanyaan as $q) {
            $val = $this->request->getPost('q_' . $q['id']);
            if ($val === null || $val === '') {
                continue;
            }
            $data = [
                'pertanyaan_id' => $q['id'],
                'user_id' => $userId,
                'nilai' => $q['tipe'] === 'skala' ? (int) $val : null,
                'jawaban_teks' => $q['tipe'] === 'teks' ? trim((string) $val) : null,
                'created_at' => $now,
            ];
            $this->db->table('survei_jawaban')->replace($data);
        }

        return redirect()->to('/pencaker/survei')->with('success', 'Terima kasih! Masukan dan penilaian survei Anda telah tersimpan.');
    }
}
