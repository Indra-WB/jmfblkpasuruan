<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // 1. Pengaturan
        $pengaturan = [
            ['key' => 'nama_instansi', 'value' => 'UPT Balai Latihan Kerja (BLK) Pasuruan', 'updated_at' => $now],
            ['key' => 'sub_instansi', 'value' => 'Dinas Tenaga Kerja dan Transmigrasi Provinsi Jawa Timur', 'updated_at' => $now],
            ['key' => 'alamat_instansi', 'value' => 'Jl. Pahlawan Sunaryo No.96s, Pandaan, Kab. Pasuruan, Jawa Timur 67156', 'updated_at' => $now],
            ['key' => 'telp_instansi', 'value' => '(0343) 631538', 'updated_at' => $now],
            ['key' => 'email_instansi', 'value' => 'jmf@uptblkpasuruan.com', 'updated_at' => $now],
            ['key' => 'website_instansi', 'value' => 'https://uptblkpasuruan.com', 'updated_at' => $now],
            ['key' => 'kepala_blk', 'value' => 'Drs. H. Nama Kepala UPT, M.Si.', 'updated_at' => $now],
            ['key' => 'nip_kepala_blk', 'value' => '19700101 199503 1 001', 'updated_at' => $now],
            ['key' => 'smtp_host', 'value' => 'mail.uptblkpasuruan.com', 'updated_at' => $now],
            ['key' => 'smtp_port', 'value' => '465', 'updated_at' => $now],
            ['key' => 'smtp_user', 'value' => 'jmf@uptblkpasuruan.com', 'updated_at' => $now],
            ['key' => 'smtp_pass', 'value' => '', 'updated_at' => $now],
            ['key' => 'smtp_crypto', 'value' => 'ssl', 'updated_at' => $now],
            ['key' => 'smtp_from_name', 'value' => 'Panitia Job Market Fair BLK Pasuruan', 'updated_at' => $now],
        ];
        foreach ($pengaturan as $p) {
            $this->db->table('pengaturan')->replace($p);
        }

        // 2. Kecamatan & Desa Kab. Pasuruan (24 Kecamatan)
        $wilayah = [
            'Bangil'         => ['Bendo Mungal', 'Dermo', 'Gempeng', 'Kalianyar', 'Kalirejo', 'Kauman', 'Kersikan', 'Kidul Dalem', 'Kolursari', 'Latek', 'Manaruwi', 'Masangan', 'Pogar', 'Raci', 'Tambakan'],
            'Beji'           => ['Baujeng', 'Beji', 'Cangkringmalang', 'Gajahbendo', 'Glanggang', 'Gununggangsir', 'Gunungsari', 'Kedungboto', 'Kedungringin', 'Kenep', 'Ngembe', 'Pagak', 'Sidowayah', 'Wonokoyo'],
            'Gempol'         => ['BuluSari', 'Carat', 'Gempol', 'Jerukpurut', 'Karangrejo', 'Kejapanan', 'Kepulungan', 'Legok', 'Ngerong', 'Randupitu', 'Sumbersuko', 'Watukosek', 'Winong', 'Wonosunyo', 'Wonoasri'],
            'Gondangwetan'   => ['Bajangan', 'Bayeman', 'Brambang', 'Gayasari', 'Gondangrejo', 'Gondangwetan', 'Kalirejo', 'Karangsentul', 'Kersikan', 'Lajuk', 'Pateguhan', 'Pekangkungan', 'Ranggeh', 'Sekarputih', 'Tenggilisrejo', 'Wonojati', 'Wonosari'],
            'Grati'          => ['Cukurgondang', 'Gratitunon', 'Kalipang', 'Kambinganrejo', 'Karangkliwon', 'Karanglo', 'Kebonrejo', 'Kedawungkulon', 'Kedawungwetan', 'Plososari', 'Ranuklindungan', 'Rebalas', 'Sumberagung', 'Sumberdawesari', 'Trewung'],
            'Kejayan'        => ['Ambal Ambil', 'Benerwojo', 'Cobanjoyo', 'Kalisat', 'Kedemungan', 'Kejayan', 'Kepuh', 'Ketangirejo', 'Klangrong', 'Klinter', 'Kurung', 'Linggo', 'Lorokan', 'Luwuk', 'Oro-oro Puleh', 'Pacarkeling', 'Patebon', 'Randugong', 'Sladi', 'Sumberbanteng', 'Sumbersuko', 'Tanggulangin', 'Tondosuru', 'Wrati'],
            'Kraton'         => ['Asemkandang', 'Bendungan', 'Curahdukuh', 'Dhompo', 'Gambirkuning', 'Gerongan', 'Jeruk', 'Kalirejo', 'Karanganyar', 'Kebotohan', 'Klampisrejo', 'Kraton', 'Mulyorejo', 'Ngabar', 'Ngempit', 'Plinggisan', 'Pukul', 'Pulokerto', 'Rejosari', 'Semare', 'Sidogiri', 'Slambrit', 'Tambakrejo', 'Tambaksari', 'ambyarz'],
            'Lekok'          => ['Alastlogo', 'Balunganyar', 'Branang', 'Gejugjati', 'Jatirejo', 'Pasinan', 'Rowogempol', 'Semendung', 'Tambaklekok', 'Tampung', 'Wates'],
            'Lumbang'        => ['Banjarsari', 'Bulukandang', 'Cukurguling', 'Karangarson', 'Karangjati', 'Kronto', 'Lumbang', 'ancaran', 'Panditan', 'Watulumbung', 'Welulang', 'Wonorejo'],
            'Nguling'        => ['Dandang Gendis', 'Kapasan', 'Kedawang', 'Mlaten', 'Nguling', 'Penunggul', 'Randuati', 'Sanganom', 'Sebalong', 'Sedarum', 'Sumberejo', 'Sudimulyo', 'Watuprapat', 'Wotgalih'],
            'Pandaan'        => ['Banjarsari', 'Banjarkejen', 'Durensewu', 'Jogosari', 'Karangjati', 'Kebonwaris', 'Kemirisewu', 'Kutorejo', 'Nogosari', 'Pandaan', 'Petungasri', 'Plintahan', 'Sebani', 'Sumbergedang', 'Sumberrejo', 'Tawangrejo', 'Tunggulwulung', 'Wedoro'],
            'Pasrepan'       => ['Ampelsari', 'Cengkrong', 'Galih', 'Jogorepuh', 'Karanglo', 'Klakah', 'Lemahbang', 'Mangguan', 'Ngantungan', 'Pasrepan', 'Petung', 'Pohgedang', 'Pohgading', 'Rejosalam', 'Sapulante', 'Sibon', 'Tambakrejo'],
            'Pohjentrek'     => ['Logowok', 'Parasrejo', 'Pleret', 'Sukorejo', 'Sungi Wetan', 'Sungi Kulon', 'Susukanrejo', 'Tidu', 'Warungdowo'],
            'Prigen'         => ['Bulukandang', 'Candiwates', 'Dayurejo', 'Gambiran', 'Jatiarjo', 'Ketangirejo', 'Ledug', 'Lumbangrejo', 'Pecalukan', 'Prigen', 'Sekarjoho', 'Sukolelo', 'Sukoreno', 'Watuagung'],
            'Purwodadi'      => ['Capang', 'Cowek', 'Dawuhan Sengon', 'Gajahrejo', 'Gerbo', 'Jatisari', 'Lebakrejo', 'Parerejo', 'Pucangsari', 'Purwodadi', 'Semut', 'Sentul', 'Tambaksari'],
            'Purwosari'      => ['Bakalan', 'Cendono', 'Karangrejo', 'Kayoman', 'Kertosari', 'Martopuro', 'Pager', 'Pucangsari', 'Purwosari', 'Sekarmojo', 'Sengonagung', 'Sukodermo', 'Sumbersuko', 'Tejowangi'],
            'Puspo'          => ['Janjangwulung', 'Jimbaran', 'Keduwung', 'Kemiri', 'Palangsari', 'Puspo', 'Puspobudo'],
            'Rejoso'         => ['Arjosari', 'Jarangan', 'Karangpandan', 'Kawisrejo', 'Kedungbako', 'Kemantrenrejo', 'Ketegan', 'Manikrejo', 'Pandanrejo', 'Patuguran', 'Rejoso Kidul', 'Rejoso Lor', 'Sadengrejo', 'Sambirejo', 'Segoropuro', 'Toyaning'],
            'Rembang'        => ['Genengwaru', 'Kalisat', 'Kanigoro', 'Kedungbanteng', 'Krengih', 'Mojoparon', 'Oro-oro Bulu', 'Oro-oro Ombo Kulon', 'Oro-oro Ombo Wetan', 'Pajaran', 'Pandean', 'Pejangkungan', 'pekoren', 'Rembang', 'Sumberglagah', 'Tampico'],
            'Sukorejo'       => ['Candibinangun', 'Curahrejo', 'Dukuhsari', 'Glagahsari', 'Gunting', 'Kalirejo', 'Karangsono', 'Kenduruan', 'Lecari', 'Lemahbang', 'Mojotengah', 'Ngadimulyo', 'Pakukerto', 'Sebandung', 'Sukorame', 'Sukorejo', 'Suwayuwo', 'Tanjungarum', 'Wonokerto'],
            'Tosari'         => ['Baledono', 'Kandangan', 'Mororejo', 'Ngadiwono', 'Podokoyo', 'Sedaeng', 'Tosari', 'Wonokitri'],
            'Tutur'          => ['Andonosari', 'Blarang', 'Gendro', 'Kaliombo', 'Kayukebek', 'Ngadirejo', 'Ngembal', 'Pungging', 'Sumberpitu', 'Tlogosari', 'Tutur', 'Wonosari'],
            'Winongan'       => ['Bandaran', 'Gading', 'Jeladri', 'Kandung', 'Karangtengah', 'Kedungrejo', 'Lebak', 'Mendalan', 'Menyarik', 'Minggir', 'Penataan', 'Prodo', 'Sidepan', 'Sruwi', 'Sumberrejo', 'Umbulan', 'Winongan Kidul', 'Winongan Lor'],
            'Wonorejo'       => ['Cobanblimbing', 'Jatigunting', 'Karangasem', 'Karangjatianyar', 'Karangmenggah', 'Karangsono', 'Kendangdukuh', 'Kluwut', 'Lebaksari', 'Pakijangan', 'Rebono', 'Sambisirah', 'Tamansari', 'Jati', 'Wonorejo'],
        ];

        $desaMap = [];
        foreach ($wilayah as $kecNama => $desaList) {
            $this->db->table('kecamatan')->insert(['nama' => $kecNama]);
            $kecId = $this->db->insertID();
            foreach ($desaList as $desaNama) {
                $this->db->table('desa')->insert([
                    'kecamatan_id' => $kecId,
                    'nama'         => ucfirst($desaNama),
                ]);
                $desaMap[$kecNama . ':' . $desaNama] = $this->db->insertID();
            }
        }
        $defaultDesaId = reset($desaMap);

        // 3. Users (Admin, Petugas, Pimpinan, 3 Perusahaan, 3 Pencaker)
        $users = [
            [
                'nama'       => 'Administrator BLK Pasuruan',
                'email'      => 'admin@uptblkpasuruan.com',
                'password'   => password_hash('Admin123!', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Petugas Meja Registrasi 1',
                'email'      => 'petugas@uptblkpasuruan.com',
                'password'   => password_hash('Petugas123!', PASSWORD_DEFAULT),
                'role'       => 'petugas',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Kepala UPT BLK / Pimpinan Disnaker',
                'email'      => 'pimpinan@uptblkpasuruan.com',
                'password'   => password_hash('Pimpinan123!', PASSWORD_DEFAULT),
                'role'       => 'pimpinan',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        foreach ($users as $u) {
            $this->db->table('users')->insert($u);
        }

        // 4. Event Aktif
        $this->db->table('events')->insert([
            'nama'               => 'Job Market Fair UPT BLK Pasuruan ' . date('Y'),
            'tahun'              => date('Y'),
            'tanggal'            => $today,
            'jam_mulai'          => '08:00:00',
            'jam_selesai'        => '16:00:00',
            'lokasi'             => 'Aula & Halaman Utama UPT BLK Pasuruan, Jl. Pahlawan Sunaryo No.96s, Pandaan',
            'deskripsi'          => 'Kegiatan bursa kerja tahunan resmi UPT Balai Latihan Kerja Pasuruan yang mempertemukan puluhan perusahaan industri di Jawa Timur dengan ribuan pencari kerja dan alumni pelatihan vokasi.',
            'pendaftaran_dibuka' => 1,
            'lamaran_dibuka'     => 1,
            'batas_seleksi'      => date('Y-m-d', strtotime('+30 days')),
            'sesi_aktif'         => 1,
            'aturan_luar_sesi'   => 'peringatan',
            'status'             => 'aktif',
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);
        $eventId = $this->db->insertID();

        // 5. Event Sesi Kedatangan
        $sesiData = [
            ['event_id' => $eventId, 'nama' => 'Sesi 1 (Pagi)', 'tanggal' => $today, 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:30:00', 'kuota' => 1000, 'urutan' => 1, 'created_at' => $now],
            ['event_id' => $eventId, 'nama' => 'Sesi 2 (Siang)', 'tanggal' => $today, 'jam_mulai' => '10:30:00', 'jam_selesai' => '13:00:00', 'kuota' => 1000, 'urutan' => 2, 'created_at' => $now],
            ['event_id' => $eventId, 'nama' => 'Sesi 3 (Sore)', 'tanggal' => $today, 'jam_mulai' => '13:00:00', 'jam_selesai' => '16:00:00', 'kuota' => 1000, 'urutan' => 3, 'created_at' => $now],
        ];
        $sesiIds = [];
        foreach ($sesiData as $s) {
            $this->db->table('event_sesi')->insert($s);
            $sesiIds[] = $this->db->insertID();
        }

        // 6. Perusahaan & Lowongan Sample
        $companies = [
            [
                'email'        => 'hrd@pier-industri.co.id',
                'nama'         => 'PT Pasuruan Industrial Estate Rembang (PIER)',
                'bidang_usaha' => 'Kawasan Industri & Manufaktur',
                'alamat'       => 'Jl. Raya Surabaya - Probolinggo KM 50, Rembang, Pasuruan',
                'kota'         => 'Kab. Pasuruan',
                'telp'         => '(0343) 741613',
                'website'      => 'https://pier.co.id',
                'deskripsi'    => 'Pengelola kawasan industri terpadu di Kabupaten Pasuruan bagian dari grup SIER.',
                'nama_hrd'     => 'Budi Santoso, S.Psi.',
                'hp_hrd'       => '081234567801',
                'booth'        => 'A-01',
                'token'        => 'BOOTH-PIER-2026-A01',
                'lowongan'     => [
                    [
                        'jabatan'           => 'Teknisi Listrik & Otomasi Industri',
                        'deskripsi'         => 'Bertanggung jawab atas perawatan instalasi kelistrikan gedung, panel kontrol PLC, dan gardu distribusi kawasan industri.',
                        'kualifikasi'       => "1. Pendidikan minimal SMK Teknik Listrik / Elektronika atau D3 Elektro\n2. Menguasai pembacaan wiring diagram dan dasar PLC\n3. Diutamakan memiliki sertifikat pelatihan kejuruan Listrik/Otomasi BLK\n4. Bersedia bekerja sistem shift",
                        'pendidikan_min'    => 'SMK',
                        'jenis_kelamin'     => 'L',
                        'usia_max'          => 28,
                        'kuota'             => 10,
                        'gaji_min'          => 4600000,
                        'gaji_max'          => 5500000,
                        'lokasi_kerja'      => 'Rembang, Kab. Pasuruan',
                        'ramah_disabilitas' => 0,
                    ],
                    [
                        'jabatan'           => 'Staf Administrasi & Layanan Tenant',
                        'deskripsi'         => 'Melayani administrasi kebutuhan perusahaan tenant di kawasan industri dan pengelolaan dokumen operasional.',
                        'kualifikasi'       => "1. Pendidikan minimal D3/S1 Administrasi Bisnis, Manajemen, atau Akuntansi\n2. Mahir Microsoft Office & pengolahan data\n3. Komunikatif dan rapi dalam pengarsipan",
                        'pendidikan_min'    => 'D3',
                        'jenis_kelamin'     => 'L/P',
                        'usia_max'          => 30,
                        'kuota'             => 5,
                        'gaji_min'          => 4500000,
                        'gaji_max'          => 5200000,
                        'lokasi_kerja'      => 'Rembang, Kab. Pasuruan',
                        'ramah_disabilitas' => 1,
                    ],
                ],
            ],
            [
                'email'        => 'recruitment@indofood-pandaan.co.id',
                'nama'         => 'PT Indofood CBP Sukses Makmur Tbk (Plant Pandaan)',
                'bidang_usaha' => 'Industri Makanan & Minuman (FMCG)',
                'alamat'       => 'Jl. Raya Surabaya - Malang KM 48, Suwayuwo, Sukorejo, Pasuruan',
                'kota'         => 'Kab. Pasuruan',
                'telp'         => '(0343) 631455',
                'website'      => 'https://indofoodcbp.com',
                'deskripsi'    => 'Perusahaan produsen produk konsumen bermerek terkemuka di Indonesia dengan fasilitas pabrik modern di Pandaan/Sukorejo.',
                'nama_hrd'     => 'Siti Aminah, M.M.',
                'hp_hrd'       => '081234567802',
                'booth'        => 'A-02',
                'token'        => 'BOOTH-ICBP-2026-A02',
                'lowongan'     => [
                    [
                        'jabatan'           => 'Operator Produksi & Pengemasan',
                        'deskripsi'         => 'Mengoperasikan mesin lini produksi makanan dan memastikan standar mutu kebersihan GMP/HACCP terpenuhi.',
                        'kualifikasi'       => "1. Pendidikan minimal SMA/SMK semua jurusan (diutamakan Teknik Mesin / Pengolahan Pangan)\n2. Sehat jasmani dan tidak buta warna\n3. Disiplin, cekatan, dan siap kerja 3 shift",
                        'pendidikan_min'    => 'SMA',
                        'jenis_kelamin'     => 'L/P',
                        'usia_max'          => 25,
                        'kuota'             => 35,
                        'gaji_min'          => 4650000,
                        'gaji_max'          => 5000000,
                        'lokasi_kerja'      => 'Sukorejo, Kab. Pasuruan',
                        'ramah_disabilitas' => 0,
                    ],
                    [
                        'jabatan'           => 'Quality Control (QC) Inspector',
                        'deskripsi'         => 'Melakukan pengujian sampel bahan baku dan produk jadi di laboratorium pabrik.',
                        'kualifikasi'       => "1. Pendidikan minimal SMK Kimia Analis / D3 Teknologi Pangan / Kimia\n2. Teliti dan memahami prosedur laboratorium",
                        'pendidikan_min'    => 'D3',
                        'jenis_kelamin'     => 'L/P',
                        'usia_max'          => 27,
                        'kuota'             => 8,
                        'gaji_min'          => 4800000,
                        'gaji_max'          => 5800000,
                        'lokasi_kerja'      => 'Sukorejo, Kab. Pasuruan',
                        'ramah_disabilitas' => 0,
                    ],
                ],
            ],
            [
                'email'        => 'karir@yamaha-electronics-pasuruan.co.id',
                'nama'         => 'PT Yamaha Musical Products Asia',
                'bidang_usaha' => 'Manufaktur Alat Musik & Elektronik',
                'alamat'       => 'Kawasan Industri PIER Jl. Rembang Industri I No.36, Pasuruan',
                'kota'         => 'Kab. Pasuruan',
                'telp'         => '(0343) 740255',
                'website'      => 'https://id.yamaha.com',
                'deskripsi'    => 'Pabrik manufaktur instrumen musik berstandar global yang berorientasi ekspor.',
                'nama_hrd'     => 'Hendra Wijaya, S.T.',
                'hp_hrd'       => '081234567803',
                'booth'        => 'A-03',
                'token'        => 'BOOTH-YMPA-2026-A03',
                'lowongan'     => [
                    [
                        'jabatan'           => 'Welder & Operator Mesin CNC',
                        'deskripsi'         => 'Melakukan proses pengelasan presisi dan pengoperasian mesin bubut/milling CNC untuk komponen logam.',
                        'kualifikasi'       => "1. Pendidikan minimal SMK Teknik Pemesinan / Pengelasan\n2. Memiliki sertifikat pelatihan Las (SMAW/GMAW) atau CNC (diutamakan Alumni BLK)\n3. Mampu membaca gambar teknik dengan akurat",
                        'pendidikan_min'    => 'SMK',
                        'jenis_kelamin'     => 'L',
                        'usia_max'          => 27,
                        'kuota'             => 20,
                        'gaji_min'          => 4700000,
                        'gaji_max'          => 5600000,
                        'lokasi_kerja'      => 'Kawasan PIER, Kab. Pasuruan',
                        'ramah_disabilitas' => 0,
                    ],
                    [
                        'jabatan'           => 'IT Support & Jaringan',
                        'deskripsi'         => 'Memelihara infrastruktur jaringan LAN/WiFi pabrik, server lokal, dan troubleshooting perangkat komputer pengguna.',
                        'kualifikasi'       => "1. Pendidikan minimal SMK TKJ / D3/S1 Teknik Informatika\n2. Memahami jaringan TCP/IP, Mikrotik, dan hardware PC\n3. Terbuka untuk penyandang disabilitas daksa ringan",
                        'pendidikan_min'    => 'D3',
                        'jenis_kelamin'     => 'L/P',
                        'usia_max'          => 30,
                        'kuota'             => 3,
                        'gaji_min'          => 4900000,
                        'gaji_max'          => 6200000,
                        'lokasi_kerja'      => 'Kawasan PIER, Kab. Pasuruan',
                        'ramah_disabilitas' => 1,
                    ],
                ],
            ],
        ];

        $epIds = [];
        $lowonganIds = [];
        foreach ($companies as $c) {
            $this->db->table('users')->insert([
                'nama'       => $c['nama'],
                'email'      => $c['email'],
                'password'   => password_hash('Perusahaan123!', PASSWORD_DEFAULT),
                'role'       => 'perusahaan',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $uId = $this->db->insertID();

            $this->db->table('perusahaan')->insert([
                'user_id'      => $uId,
                'nama'         => $c['nama'],
                'bidang_usaha' => $c['bidang_usaha'],
                'alamat'       => $c['alamat'],
                'kota'         => $c['kota'],
                'telp'         => $c['telp'],
                'email'        => $c['email'],
                'website'      => $c['website'],
                'deskripsi'    => $c['deskripsi'],
                'nama_hrd'     => $c['nama_hrd'],
                'hp_hrd'       => $c['hp_hrd'],
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            $pId = $this->db->insertID();

            $this->db->table('event_perusahaan')->insert([
                'event_id'      => $eventId,
                'perusahaan_id' => $pId,
                'no_booth'      => $c['booth'],
                'qr_token'      => $c['token'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            $epId = $this->db->insertID();
            $epIds[] = $epId;

            foreach ($c['lowongan'] as $low) {
                $low['event_perusahaan_id'] = $epId;
                $low['is_active'] = 1;
                $low['created_at'] = $now;
                $low['updated_at'] = $now;
                $this->db->table('lowongan')->insert($low);
                $lowonganIds[] = ['id' => $this->db->insertID(), 'ep_id' => $epId];
            }
        }

        // 7. Sample Pencari Kerja
        $pencakerSamples = [
            [
                'email'      => 'ahmad.fauzi@gmail.com',
                'nama'       => 'Ahmad Fauzi Pratama',
                'nik'        => '3514111505010001',
                'tempat'     => 'Pasuruan',
                'tgl_lahir'  => '2001-05-15',
                'jk'         => 'L',
                'alamat'     => 'Jl. Kartini RT 02 RW 05, Pandaan',
                'domisili'   => 'kab_pasuruan',
                'desa_id'    => $desaMap['Pandaan:Pandaan'] ?? $defaultDesaId,
                'kota_luar'  => null,
                'hp'         => '085712345601',
                'pendidikan' => 'SMK',
                'jurusan'    => 'Teknik Instalasi Tenaga Listrik',
                'disabilitas'=> 0,
                'link_berkas'=> 'https://drive.google.com/file/d/1SampleAhmadFauziBerkasGabunganPDF/view?usp=sharing',
                'qr_token'   => 'PESERTA-2026-0001',
                'no_reg'     => 'JMF2026-0001',
                'checkin_at' => $now, // sudah check-in agar bisa langsung tes lamar
                'sesi_id'    => $sesiIds[0],
                'keahlian'   => ['nama' => 'Otomasi Listrik Industri (PLC)', 'penerbit' => 'UPT BLK Pasuruan', 'tahun' => 2025, 'alumni_blk' => 1],
                'pengalaman' => ['nama_perusahaan' => 'CV Karya Teknik Mandiri', 'jabatan' => 'Teknisi Magang', 'tahun_mulai' => 2024, 'tahun_selesai' => '2025'],
            ],
            [
                'email'      => 'dewi.lestari@gmail.com',
                'nama'       => 'Dewi Lestari Ningrum',
                'nik'        => '3514015208000002',
                'tempat'     => 'Pasuruan',
                'tgl_lahir'  => '2000-08-12',
                'jk'         => 'P',
                'alamat'     => 'Perum Bangil Indah Blok C-12, Bangil',
                'domisili'   => 'kab_pasuruan',
                'desa_id'    => $desaMap['Bangil:Pogar'] ?? $defaultDesaId,
                'kota_luar'  => null,
                'hp'         => '085712345602',
                'pendidikan' => 'D3',
                'jurusan'    => 'Administrasi Bisnis',
                'disabilitas'=> 0,
                'link_berkas'=> 'https://drive.google.com/file/d/1SampleDewiLestariBerkasGabunganPDF/view?usp=sharing',
                'qr_token'   => 'PESERTA-2026-0002',
                'no_reg'     => 'JMF2026-0002',
                'checkin_at' => $now,
                'sesi_id'    => $sesiIds[0],
                'keahlian'   => ['nama' => 'Practical Office Advance', 'penerbit' => 'UPT BLK Pasuruan', 'tahun' => 2024, 'alumni_blk' => 1],
                'pengalaman' => ['nama_perusahaan' => 'PT Mitra Niaga Jaya', 'jabatan' => 'Admin Gudang', 'tahun_mulai' => 2023, 'tahun_selesai' => '2025'],
            ],
            [
                'email'      => 'rizky.akbar@gmail.com',
                'nama'       => 'Rizky Akbar Maulana',
                'nik'        => '3575011001990003',
                'tempat'     => 'Kota Pasuruan',
                'tgl_lahir'  => '1999-01-10',
                'jk'         => 'L',
                'alamat'     => 'Jl. Panglima Sudirman No. 45, Purworejo',
                'domisili'   => 'luar',
                'desa_id'    => null,
                'kota_luar'  => 'Kota Pasuruan',
                'hp'         => '085712345603',
                'pendidikan' => 'D4/S1',
                'jurusan'    => 'Teknik Informatika',
                'disabilitas'=> 1,
                'jenis_dis'  => 'Disabilitas Daksa Ringan',
                'link_berkas'=> 'https://drive.google.com/file/d/1SampleRizkyAkbarBerkasGabunganPDF/view?usp=sharing',
                'qr_token'   => 'PESERTA-2026-0003',
                'no_reg'     => 'JMF2026-0003',
                'checkin_at' => null, // belum check-in (untuk bahan demo scan petugas)
                'sesi_id'    => $sesiIds[1],
                'keahlian'   => ['nama' => 'Junior Web Developer (BNSP)', 'penerbit' => 'UPT BLK Pasuruan', 'tahun' => 2025, 'alumni_blk' => 1],
                'pengalaman' => ['nama_perusahaan' => 'Freelance Web Studio', 'jabatan' => 'IT Support & Web Dev', 'tahun_mulai' => 2023, 'tahun_selesai' => 'Sekarang'],
            ],
        ];

        $pesertaIds = [];
        foreach ($pencakerSamples as $ps) {
            $this->db->table('users')->insert([
                'nama'       => $ps['nama'],
                'email'      => $ps['email'],
                'password'   => password_hash('Pencaker123!', PASSWORD_DEFAULT),
                'role'       => 'pencaker',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $uId = $this->db->insertID();

            $this->db->table('pencaker')->insert([
                'user_id'           => $uId,
                'nik'               => $ps['nik'],
                'nama'              => $ps['nama'],
                'tempat_lahir'      => $ps['tempat'],
                'tgl_lahir'         => $ps['tgl_lahir'],
                'jk'                => $ps['jk'],
                'alamat'            => $ps['alamat'],
                'domisili'          => $ps['domisili'],
                'desa_id'           => $ps['desa_id'],
                'kota_luar'         => $ps['kota_luar'],
                'hp'                => $ps['hp'],
                'pendidikan'        => $ps['pendidikan'],
                'jurusan'           => $ps['jurusan'],
                'disabilitas'       => $ps['disabilitas'],
                'jenis_disabilitas' => $ps['jenis_dis'] ?? null,
                'foto'              => null,
                'link_berkas'       => $ps['link_berkas'],
                'profil_lengkap'    => 1,
                'setuju_pdp'        => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
            $pckId = $this->db->insertID();

            $this->db->table('keahlian')->insert([
                'pencaker_id' => $pckId,
                'nama'        => $ps['keahlian']['nama'],
                'penerbit'    => $ps['keahlian']['penerbit'],
                'tahun'       => $ps['keahlian']['tahun'],
                'alumni_blk'  => $ps['keahlian']['alumni_blk'],
                'created_at'  => $now,
            ]);

            $this->db->table('pengalaman_kerja')->insert([
                'pencaker_id'     => $pckId,
                'nama_perusahaan' => $ps['pengalaman']['nama_perusahaan'],
                'jabatan'         => $ps['pengalaman']['jabatan'],
                'tahun_mulai'     => $ps['pengalaman']['tahun_mulai'],
                'tahun_selesai'   => $ps['pengalaman']['tahun_selesai'],
                'created_at'      => $now,
            ]);

            $this->db->table('event_peserta')->insert([
                'event_id'          => $eventId,
                'pencaker_id'       => $pckId,
                'sesi_id'           => $ps['sesi_id'],
                'no_registrasi'     => $ps['no_reg'],
                'qr_token'          => $ps['qr_token'],
                'checkin_at'        => $ps['checkin_at'],
                'checkin_by'        => $ps['checkin_at'] ? 2 : null,
                'checkin_luar_sesi' => 0,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
            $pesertaIds[] = $this->db->insertID();
        }

        // Sample Lamaran
        $this->db->table('lamaran')->insert([
            'event_peserta_id'    => $pesertaIds[0],
            'lowongan_id'         => $lowonganIds[0]['id'],
            'event_perusahaan_id' => $lowonganIds[0]['ep_id'],
            'status'              => 'LolosBerkas',
            'catatan'             => 'Kualifikasi sertifikat BLK sesuai.',
            'created_at'          => $now,
            'updated_at'          => $now,
        ]);
        $lam1 = $this->db->insertID();
        $this->db->table('lamaran_log')->insert([
            'lamaran_id'  => $lam1,
            'status_lama' => null,
            'status_baru' => 'Dikirim',
            'catatan'     => 'Melamar melalui scan QR Booth',
            'created_at'  => $now,
        ]);
        $this->db->table('lamaran_log')->insert([
            'lamaran_id'  => $lam1,
            'status_lama' => 'Dikirim',
            'status_baru' => 'LolosBerkas',
            'catatan'     => 'Kualifikasi sertifikat BLK sesuai.',
            'created_at'  => $now,
        ]);

        $this->db->table('lamaran')->insert([
            'event_peserta_id'    => $pesertaIds[1],
            'lowongan_id'         => $lowonganIds[1]['id'],
            'event_perusahaan_id' => $lowonganIds[1]['ep_id'],
            'status'              => 'Diterima',
            'catatan'             => 'Diterima sebagai Staf Administrasi mulai bulan depan.',
            'created_at'          => $now,
            'updated_at'          => $now,
        ]);
        $lam2 = $this->db->insertID();
        $this->db->table('lamaran_log')->insert([
            'lamaran_id'  => $lam2,
            'status_lama' => 'PanggilTes',
            'status_baru' => 'Diterima',
            'catatan'     => 'Diterima sebagai Staf Administrasi mulai bulan depan.',
            'created_at'  => $now,
        ]);

        // 8. Survei Pertanyaan Bawaan (7 Pencaker + 7 Perusahaan)
        $surveiPencaker = [
            ['pertanyaan' => 'Kemudahan proses pendaftaran online', 'tipe' => 'skala', 'urutan' => 1],
            ['pertanyaan' => 'Kecepatan & keramahan layanan check-in', 'tipe' => 'skala', 'urutan' => 2],
            ['pertanyaan' => 'Kemudahan melamar lewat scan QR booth', 'tipe' => 'skala', 'urutan' => 3],
            ['pertanyaan' => 'Kesesuaian lowongan dengan pendidikan/keahlian Anda', 'tipe' => 'skala', 'urutan' => 4],
            ['pertanyaan' => 'Kenyamanan lokasi & penataan acara', 'tipe' => 'skala', 'urutan' => 5],
            ['pertanyaan' => 'Kepuasan keseluruhan terhadap Job Market Fair', 'tipe' => 'skala', 'urutan' => 6],
            ['pertanyaan' => 'Saran dan masukan untuk pelaksanaan berikutnya', 'tipe' => 'teks', 'urutan' => 7],
        ];
        foreach ($surveiPencaker as $sp) {
            $sp['event_id'] = $eventId;
            $sp['target'] = 'pencaker';
            $sp['created_at'] = $now;
            $this->db->table('survei_pertanyaan')->insert($sp);
        }

        $surveiPerusahaan = [
            ['pertanyaan' => 'Kemudahan penggunaan aplikasi (kelola lowongan & pelamar)', 'tipe' => 'skala', 'urutan' => 1],
            ['pertanyaan' => 'Kualitas/kesesuaian pelamar dengan kebutuhan perusahaan', 'tipe' => 'skala', 'urutan' => 2],
            ['pertanyaan' => 'Pelayanan panitia BLK Pasuruan', 'tipe' => 'skala', 'urutan' => 3],
            ['pertanyaan' => 'Fasilitas booth & lokasi acara', 'tipe' => 'skala', 'urutan' => 4],
            ['pertanyaan' => 'Minat perusahaan untuk ikut kembali tahun depan', 'tipe' => 'skala', 'urutan' => 5],
            ['pertanyaan' => 'Kepuasan keseluruhan terhadap pelaksanaan Job Market Fair', 'tipe' => 'skala', 'urutan' => 6],
            ['pertanyaan' => 'Saran dan masukan untuk panitia BLK Pasuruan', 'tipe' => 'teks', 'urutan' => 7],
        ];
        foreach ($surveiPerusahaan as $sr) {
            $sr['event_id'] = $eventId;
            $sr['target'] = 'perusahaan';
            $sr['created_at'] = $now;
            $this->db->table('survei_pertanyaan')->insert($sr);
        }
    }
}
