<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::index');
$routes->get('lowongan', 'Home::lowongan');
$routes->get('lowongan/(:num)', 'Home::detailLowongan/$1');
$routes->get('perusahaan-peserta', 'Home::perusahaan');
$routes->get('api/desa/(:num)', 'Home::desaByKecamatan/$1');
$routes->get('media/foto/(:segment)', 'Home::foto/$1');
$routes->get('media/logo/(:segment)', 'Home::logo/$1');

// Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('daftar', 'Auth::daftar');
$routes->post('daftar', 'Auth::attemptDaftar');
$routes->get('logout', 'Auth::logout');

// QR Booth Direct Scan Target (/lamar/{token})
$routes->get('lamar/(:segment)', 'Pencaker\Panel::lamarBooth/$1', ['filter' => 'auth:pencaker']);
$routes->post('lamar/(:segment)', 'Pencaker\Panel::submitLamarBooth/$1', ['filter' => 'auth:pencaker']);

// Pencari Kerja Routes
$routes->group('pencaker', ['filter' => 'auth:pencaker'], static function ($routes) {
    $routes->get('/', 'Pencaker\Panel::index');
    $routes->get('profil', 'Pencaker\Panel::profil');
    $routes->post('profil', 'Pencaker\Panel::saveProfil');
    $routes->post('pengalaman/tambah', 'Pencaker\Panel::addPengalaman');
    $routes->post('pengalaman/hapus/(:num)', 'Pencaker\Panel::deletePengalaman/$1');
    $routes->post('keahlian/tambah', 'Pencaker\Panel::addKeahlian');
    $routes->post('keahlian/hapus/(:num)', 'Pencaker\Panel::deleteKeahlian/$1');
    $routes->post('event/daftar', 'Pencaker\Panel::daftarEvent');
    $routes->post('event/pindah-sesi', 'Pencaker\Panel::pindahSesi');
    $routes->get('kartu-qr', 'Pencaker\Panel::kartuQr');
    $routes->get('lamaran', 'Pencaker\Panel::lamaran');
    $routes->get('scan-booth', 'Pencaker\Panel::scanBooth');
    $routes->get('survei', 'Pencaker\Panel::survei');
    $routes->post('survei', 'Pencaker\Panel::saveSurvei');
});

// Perusahaan Routes
$routes->group('perusahaan', ['filter' => 'auth:perusahaan'], static function ($routes) {
    $routes->get('/', 'Perusahaan\Panel::index');
    $routes->get('profil', 'Perusahaan\Panel::profil');
    $routes->post('profil', 'Perusahaan\Panel::saveProfil');
    $routes->get('lowongan', 'Perusahaan\Panel::lowongan');
    $routes->post('lowongan/simpan', 'Perusahaan\Panel::saveLowongan');
    $routes->post('lowongan/hapus/(:num)', 'Perusahaan\Panel::deleteLowongan/$1');
    $routes->get('pelamar', 'Perusahaan\Panel::pelamar');
    $routes->get('pelamar/detail/(:num)', 'Perusahaan\Panel::detailPelamar/$1');
    $routes->post('pelamar/status', 'Perusahaan\Panel::updateStatus');
    $routes->post('pelamar/status-massal', 'Perusahaan\Panel::updateStatusMassal');
    $routes->get('pelamar/export-excel', 'Perusahaan\Panel::exportExcel');
    $routes->get('penempatan', 'Perusahaan\Panel::penempatan');
    $routes->get('penempatan/excel', 'Perusahaan\Panel::exportPenempatanExcel');
    $routes->get('poster-qr', 'Perusahaan\Panel::posterQr');
    $routes->get('survei', 'Perusahaan\Panel::survei');
    $routes->post('survei', 'Perusahaan\Panel::saveSurvei');
});

// Petugas Registrasi Routes
$routes->group('petugas', ['filter' => 'auth:petugas,admin'], static function ($routes) {
    $routes->get('/', 'Petugas\Checkin::index');
    $routes->post('verify', 'Petugas\Checkin::verify');
    $routes->post('confirm', 'Petugas\Checkin::confirm');
    $routes->get('cari', 'Petugas\Checkin::cari');
});

// Pimpinan / Disnaker Routes (Read-Only Dashboard & Reports)
$routes->group('pimpinan', ['filter' => 'auth:pimpinan,admin'], static function ($routes) {
    $routes->get('/', 'Pimpinan\Dashboard::index');
    $routes->get('realtime-stats', 'Pimpinan\Dashboard::realtimeStats');
    $routes->get('penempatan', 'Pimpinan\Dashboard::penempatan');
    $routes->get('penempatan/excel', 'Pimpinan\Dashboard::exportPenempatanExcel');
    $routes->get('laporan', 'Pimpinan\Dashboard::laporan');
    $routes->get('laporan/excel/(:segment)', 'Pimpinan\Dashboard::exportExcel/$1');
    $routes->get('laporan/pdf/(:segment)', 'Pimpinan\Dashboard::exportPdf/$1');
});

// Admin BLK Routes
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('/', 'Admin\Panel::index');
    $routes->get('events', 'Admin\Panel::events');
    $routes->post('events/simpan', 'Admin\Panel::saveEvent');
    $routes->get('sesi', 'Admin\Panel::sesi');
    $routes->post('sesi/pengaturan', 'Admin\Panel::savePengaturanSesi');
    $routes->post('sesi/simpan', 'Admin\Panel::saveSesi');
    $routes->post('sesi/hapus/(:num)', 'Admin\Panel::deleteSesi/$1');
    $routes->post('sesi/pindah-peserta', 'Admin\Panel::pindahPesertaSesi');
    $routes->post('sesi/bagi-otomatis', 'Admin\Panel::bagiOtomatisSesi');

    $routes->get('perusahaan', 'Admin\Panel::perusahaan');
    $routes->post('perusahaan/simpan', 'Admin\Panel::savePerusahaan');
    $routes->post('perusahaan/reset-password/(:num)', 'Admin\Panel::resetPasswordPerusahaan/$1');
    $routes->post('perusahaan/toggle/(:num)', 'Admin\Panel::toggleUser/$1');
    $routes->post('perusahaan/booth', 'Admin\Panel::saveBooth');
    $routes->get('perusahaan/poster-qr/(:num)', 'Admin\Panel::posterQr/$1');
    $routes->get('perusahaan/poster-qr-semua', 'Admin\Panel::posterQrSemua');

    $routes->get('lowongan', 'Admin\Panel::lowongan');
    $routes->post('lowongan/toggle/(:num)', 'Admin\Panel::toggleLowongan/$1');

    $routes->get('pencaker', 'Admin\Panel::pencaker');
    $routes->get('pencaker/detail/(:num)', 'Admin\Panel::detailPencaker/$1');
    $routes->post('pencaker/reset-password/(:num)', 'Admin\Panel::resetPasswordPencaker/$1');
    $routes->post('pencaker/toggle/(:num)', 'Admin\Panel::toggleUser/$1');

    $routes->get('pengguna', 'Admin\Panel::pengguna');
    $routes->post('pengguna/simpan', 'Admin\Panel::savePengguna');
    $routes->post('pengguna/toggle/(:num)', 'Admin\Panel::toggleUser/$1');

    $routes->get('survei', 'Admin\Panel::survei');
    $routes->post('survei/simpan', 'Admin\Panel::saveSurveiPertanyaan');
    $routes->post('survei/hapus/(:num)', 'Admin\Panel::deleteSurveiPertanyaan/$1');

    $routes->get('pengaturan', 'Admin\Panel::pengaturan');
    $routes->post('pengaturan', 'Admin\Panel::savePengaturan');
    $routes->post('email/proses', 'Admin\Panel::prosesEmailQueue');

    $routes->get('penempatan', 'Pimpinan\Dashboard::penempatan');
    $routes->get('penempatan/excel', 'Pimpinan\Dashboard::exportPenempatanExcel');
    $routes->get('laporan', 'Pimpinan\Dashboard::laporan');
});
