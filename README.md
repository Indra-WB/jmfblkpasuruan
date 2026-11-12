# Sistem Informasi Job Market Fair — UPT BLK Pasuruan

Aplikasi web resmi untuk memfasilitasi penyelenggaraan **Job Market Fair (Bursa Kerja)** tahunan di **UPT Balai Latihan Kerja (BLK) Pasuruan — Dinas Tenaga Kerja dan Transmigrasi Provinsi Jawa Timur**.

Dibangun menggunakan **CodeIgniter 4**, **MySQL**, dan **Bootstrap 5** dengan alur *paperless* berbasis pemindaian **QR Code** mulai dari check-in kehadiran di meja registrasi hingga pengiriman lamaran kerja langsung di booth perusahaan.

---

## Fitur Utama

### 1. Pencari Kerja (Peserta / Pelamar)
- **Akun Multi-Tahun:** Akun dan profil tersimpan lintas tahun (cukup 1 kali daftar untuk mengikuti event tahun-tahun berikutnya).
- **Biodata & Wilayah Fleksibel:** Mendukung peserta berdomisili di **Kabupaten Pasuruan** (dropdown 24 Kecamatan & Desa/Kelurahan otomatis) maupun **Luar Kabupaten Pasuruan** (isian Kabupaten/Kota bebas).
- **Hemat Penyimpanan Hosting:**
  - Satu-satunya file yang diunggah ke server hanya **Pas Foto** (JPG/PNG maks. 1 MB, otomatis diperkecil).
  - Seluruh dokumen lamaran (**CV, Ijazah Terakhir, Sertifikat Pelatihan, KTP, dll.**) digabung menjadi **1 file PDF** oleh peserta dan diisikan melalui **1 input Link Google Drive**.
- **Sesi Kedatangan & Kartu QR (PDF):** Memilih sesi kedatangan yang kuotanya masih tersedia, bisa pindah sesi sebelum check-in, dan mengunduh **Kartu Peserta ber-QR Code (PDF)**.
- **Scan QR Booth & Lamar (*Paperless*):** Memindai poster QR Code di booth perusahaan menggunakan kamera HP, memilih 1 formasi jabatan, dan memantau riwayat status seleksi serta jadwal panggilan tes.

### 2. Pengaturan Sesi Kedatangan (Opsional / Anti-Penumpukan)
- Admin dapat **mengaktifkan atau menonaktifkan** fitur pembagian sesi kedatangan per event.
- Kuota pendaftar per sesi diatur oleh Admin dan dijaga dengan transaksi database (`SELECT ... FOR UPDATE`) agar kuota tidak terlampaui.
- Aturan fleksibel saat petugas memindai peserta yang hadir di luar jam sesinya: **tampilkan peringatan saja** atau **tolak check-in**.
- Tersedia fitur **Bagi Otomatis** untuk menyebar peserta tanpa sesi ke sesi yang masih kosong.

### 3. Perusahaan Peserta (Exhibitor / HRD)
- Akun dibuatkan oleh Admin BLK (1 akun per perusahaan) dan partisipasi bersifat **gratis**.
- Mengelola profil perusahaan, formasi lowongan kerja, kuota kebutuhan, serta penanda **Lowongan Ramah Disabilitas**.
- Mencetak **Poster QR Booth (PDF)** siap tempel di meja booth.
- Menyaring pelamar, membuka link PDF berkas gabungan pelamar, mengubah status seleksi (**Lolos Berkas, Panggil Tes/Interview + Jadwal & Lokasi, Diterima, Ditolak**) secara satuan maupun massal, serta mengunduh data pelamar ke **Excel (.xlsx)**.

### 4. Petugas Registrasi (Meja Depan)
- Memindai Kartu QR Peserta menggunakan kamera HP/laptop langsung dari browser atau pencarian manual berdasarkan NIK/Nama.
- Menampilkan foto peserta, detail registrasi, dan indikator kesesuaian jadwal sesi kedatangan.

### 5. Admin BLK & Pimpinan / Disnaker
- **Dashboard Real-Time Hari-H:** Grafik dan angka pendaftar vs hadir (check-in), kehadiran per sesi, jumlah lamaran masuk, dan penempatan kerja yang diperbarui otomatis setiap 15 detik.
- **Laporan Komprehensif:** Statistik pelamar per perusahaan/jabatan, demografi (gender, kelompok usia, pendidikan, kecamatan/wilayah, disabilitas, alumni BLK), tingkat penempatan kerja, dan evaluasi survei kepuasan.
- **Export Laporan:** Unduh rekapitulasi **Excel (.xlsx)** dan cetak **Laporan Resmi Berkop UPT BLK Pasuruan (PDF)**.
- **Antrean Email SMTP (`jmf@uptblkpasuruan.com`):** Notifikasi bukti registrasi dan perubahan status seleksi dikirim melalui antrean (`email_queue`) baik lewat tombol panel Admin maupun perintah Cron CLI.

---

## Teknologi & Library

| Komponen | Teknologi |
|---|---|
| Framework Backend | **CodeIgniter 4.7** (PHP 8.2+) |
| Basis Data | **MySQL / MariaDB** (18 Tabel Terindeks) |
| Frontend UI | **Bootstrap 5.3**, Bootstrap Icons, DataTables, Chart.js |
| Pemindai QR Kamera | `html5-qrcode` |
| Generator QR Code | `chillerlan/php-qrcode` |
| Generator PDF | `dompdf/dompdf` |
| Generator Excel | `phpoffice/phpspreadsheet` |

---

## Panduan Instalasi (Lokal / Hosting)

### 1. Persyaratan Sistem
- PHP **8.2** atau lebih baru (dengan ekstensi `intl`, `mbstring`, `gd`, `zip`, `mysqli`, `curl` aktif)
- MySQL / MariaDB
- Composer
- **HTTPS** aktif pada domain produksi (wajib agar browser mengizinkan akses kamera untuk pemindai QR Code)

### 2. Langkah Instalasi
1. **Clone repository:**
   ```bash
   git clone https://github.com/Indra-WB/jmfblkpasuruan.git jmf
   cd jmf
   ```
2. **Instal dependensi Composer:**
   ```bash
   composer install
   ```
3. **Konfigurasi environment (`.env`):**
   Salin file `env` menjadi `.env` lalu sesuaikan konfigurasi database:
   ```ini
   CI_ENVIRONMENT = development

   database.default.hostname = localhost
   database.default.database = jmf_blk_pasuruan
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   ```
4. **Jalankan Migrasi & Seeder Data Awal:**
   Buat database `jmf_blk_pasuruan` di MySQL, kemudian jalankan:
   ```bash
   php spark migrate
   php spark db:seed InitialSeeder
   ```
5. **Jalankan Aplikasi:**
   - Melalui XAMPP: buka `http://localhost/jmf/`
   - Atau melalui server bawaan CI4: `php spark serve`
   - Pada hosting produksi, arahkan **Document Root** domain ke folder `public/`.

---

## Akun Demo Bawaan (`InitialSeeder`)

| Role | Email | Password |
|---|---|---|
| **Admin BLK** | `admin@uptblkpasuruan.com` | `Admin123!` |
| **Petugas Registrasi** | `petugas@uptblkpasuruan.com` | `Petugas123!` |
| **Pimpinan / Disnaker** | `pimpinan@uptblkpasuruan.com` | `Pimpinan123!` |
| **Perusahaan (Booth A-01)** | `hrd@pier-industri.co.id` | `Perusahaan123!` |
| **Perusahaan (Booth A-02)** | `recruitment@indofood-pandaan.co.id` | `Perusahaan123!` |
| **Perusahaan (Booth A-03)** | `karir@yamaha-electronics-pasuruan.co.id` | `Perusahaan123!` |
| **Pencari Kerja (Sudah Check-in)** | `ahmad.fauzi@gmail.com` | `Pencaker123!` |
| **Pencari Kerja (Belum Check-in)** | `rizky.akbar@gmail.com` | `Pencaker123!` |

---

## Cron Job Antrean Email

Untuk memproses antrean email notifikasi secara terjadwal di server hosting, tambahkan perintah berikut pada Cron Job (misalnya setiap 5 menit):

```bash
php /path/to/jmf/spark email:kirim
```

---

## Dokumentasi Perancangan

Dokumen spesifikasi lengkap, diagram alur (Mermaid), dan rancangan basis data tersedia di:
- [`docs/perancangan_jobfair_blk_pasuruan.md`](docs/perancangan_jobfair_blk_pasuruan.md)
