<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllJmfTables extends Migration
{
    public function up()
    {
        // 1. users
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'       => ['type' => 'ENUM', 'constraint' => ['admin', 'petugas', 'pimpinan', 'perusahaan', 'pencaker'], 'default' => 'pencaker'],
            'is_active'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'last_login' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('role');
        $this->forge->createTable('users', true);

        // 2. kecamatan
        $this->forge->addField([
            'id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kecamatan', true);

        // 3. desa
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kecamatan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama'         => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('kecamatan_id');
        $this->forge->addForeignKey('kecamatan_id', 'kecamatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('desa', true);

        // 4. events
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'               => ['type' => 'VARCHAR', 'constraint' => 200],
            'tahun'              => ['type' => 'YEAR'],
            'tanggal'            => ['type' => 'DATE'],
            'jam_mulai'          => ['type' => 'TIME', 'default' => '08:00:00'],
            'jam_selesai'        => ['type' => 'TIME', 'default' => '15:00:00'],
            'lokasi'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'deskripsi'          => ['type' => 'TEXT', 'null' => true],
            'banner'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'pendaftaran_dibuka' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'lamaran_dibuka'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'batas_seleksi'      => ['type' => 'DATE', 'null' => true],
            'sesi_aktif'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'aturan_luar_sesi'   => ['type' => 'ENUM', 'constraint' => ['peringatan', 'tolak'], 'default' => 'peringatan'],
            'status'             => ['type' => 'ENUM', 'constraint' => ['draf', 'aktif', 'selesai'], 'default' => 'aktif'],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->createTable('events', true);

        // 5. event_sesi
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal'     => ['type' => 'DATE'],
            'jam_mulai'   => ['type' => 'TIME'],
            'jam_selesai' => ['type' => 'TIME'],
            'kuota'       => ['type' => 'INT', 'constraint' => 11, 'default' => 500],
            'urutan'      => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_sesi', true);

        // 6. perusahaan
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'bidang_usaha' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'alamat'       => ['type' => 'TEXT', 'null' => true],
            'kota'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'telp'         => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'website'      => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'logo'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'deskripsi'    => ['type' => 'TEXT', 'null' => true],
            'nama_hrd'     => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'hp_hrd'       => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('perusahaan', true);

        // 7. event_perusahaan
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'perusahaan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'no_booth'      => ['type' => 'VARCHAR', 'constraint' => 30],
            'qr_token'      => ['type' => 'VARCHAR', 'constraint' => 64],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('qr_token');
        $this->forge->addUniqueKey(['event_id', 'perusahaan_id']);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('perusahaan_id', 'perusahaan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_perusahaan', true);

        // 8. lowongan
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_perusahaan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jabatan'             => ['type' => 'VARCHAR', 'constraint' => 200],
            'deskripsi'           => ['type' => 'TEXT'],
            'kualifikasi'         => ['type' => 'TEXT'],
            'pendidikan_min'      => ['type' => 'ENUM', 'constraint' => ['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'D4/S1', 'S2'], 'default' => 'SMA'],
            'jenis_kelamin'       => ['type' => 'ENUM', 'constraint' => ['L/P', 'L', 'P'], 'default' => 'L/P'],
            'usia_max'            => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'kuota'               => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'gaji_min'            => ['type' => 'BIGINT', 'null' => true],
            'gaji_max'            => ['type' => 'BIGINT', 'null' => true],
            'lokasi_kerja'        => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => 'Pasuruan'],
            'ramah_disabilitas'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('event_perusahaan_id');
        $this->forge->addKey('pendidikan_min');
        $this->forge->addForeignKey('event_perusahaan_id', 'event_perusahaan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lowongan', true);

        // 9. pencaker
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nik'               => ['type' => 'CHAR', 'constraint' => 16],
            'nama'              => ['type' => 'VARCHAR', 'constraint' => 150],
            'tempat_lahir'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tgl_lahir'         => ['type' => 'DATE', 'null' => true],
            'jk'                => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'default' => 'L'],
            'alamat'            => ['type' => 'TEXT', 'null' => true],
            'domisili'          => ['type' => 'ENUM', 'constraint' => ['kab_pasuruan', 'luar'], 'default' => 'kab_pasuruan'],
            'desa_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'kota_luar'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'hp'                => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'pendidikan'        => ['type' => 'ENUM', 'constraint' => ['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'D4/S1', 'S2'], 'default' => 'SMK'],
            'jurusan'           => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'disabilitas'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'jenis_disabilitas' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'foto'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'link_berkas'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'profil_lengkap'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'setuju_pdp'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addUniqueKey('nik');
        $this->forge->addKey('desa_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('desa_id', 'desa', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pencaker', true);

        // 10. pengalaman_kerja
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pencaker_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_perusahaan' => ['type' => 'VARCHAR', 'constraint' => 150],
            'jabatan'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'tahun_mulai'     => ['type' => 'YEAR'],
            'tahun_selesai'   => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'Sekarang'],
            'deskripsi'       => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('pencaker_id');
        $this->forge->addForeignKey('pencaker_id', 'pencaker', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengalaman_kerja', true);

        // 11. keahlian
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pencaker_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'penerbit'    => ['type' => 'VARCHAR', 'constraint' => 150],
            'tahun'       => ['type' => 'YEAR'],
            'alumni_blk'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('pencaker_id');
        $this->forge->addForeignKey('pencaker_id', 'pencaker', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('keahlian', true);

        // 12. event_peserta
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pencaker_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sesi_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'no_registrasi'     => ['type' => 'VARCHAR', 'constraint' => 30],
            'qr_token'          => ['type' => 'VARCHAR', 'constraint' => 64],
            'checkin_at'        => ['type' => 'DATETIME', 'null' => true],
            'checkin_by'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'checkin_luar_sesi' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('qr_token');
        $this->forge->addUniqueKey('no_registrasi');
        $this->forge->addUniqueKey(['event_id', 'pencaker_id']);
        $this->forge->addKey('sesi_id');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pencaker_id', 'pencaker', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sesi_id', 'event_sesi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('event_peserta', true);

        // 13. lamaran
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_peserta_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'lowongan_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_perusahaan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'              => ['type' => 'ENUM', 'constraint' => ['Dikirim', 'LolosBerkas', 'PanggilTes', 'Diterima', 'Ditolak'], 'default' => 'Dikirim'],
            'jadwal_tes'          => ['type' => 'DATETIME', 'null' => true],
            'lokasi_tes'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'catatan'             => ['type' => 'TEXT', 'null' => true],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        // Unique constraint enforcing 1 vacancy per company per applicant!
        $this->forge->addUniqueKey(['event_peserta_id', 'event_perusahaan_id']);
        $this->forge->addKey('lowongan_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('event_peserta_id', 'event_peserta', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lowongan_id', 'lowongan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_perusahaan_id', 'event_perusahaan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lamaran', true);

        // 14. lamaran_log
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'lamaran_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status_lama' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'status_baru' => ['type' => 'VARCHAR', 'constraint' => 30],
            'catatan'     => ['type' => 'TEXT', 'null' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('lamaran_id');
        $this->forge->addForeignKey('lamaran_id', 'lamaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lamaran_log', true);

        // 15. survei_pertanyaan
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'target'     => ['type' => 'ENUM', 'constraint' => ['pencaker', 'perusahaan']],
            'pertanyaan' => ['type' => 'VARCHAR', 'constraint' => 300],
            'tipe'       => ['type' => 'ENUM', 'constraint' => ['skala', 'teks'], 'default' => 'skala'],
            'urutan'     => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['event_id', 'target']);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('survei_pertanyaan', true);

        // 16. survei_jawaban
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pertanyaan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nilai'         => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'jawaban_teks'  => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['pertanyaan_id', 'user_id']);
        $this->forge->addForeignKey('pertanyaan_id', 'survei_pertanyaan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('survei_jawaban', true);

        // 17. email_queue
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tujuan'     => ['type' => 'VARCHAR', 'constraint' => 150],
            'subjek'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'isi'        => ['type' => 'TEXT'],
            'status'     => ['type' => 'ENUM', 'constraint' => ['antre', 'terkirim', 'gagal'], 'default' => 'antre'],
            'percobaan'  => ['type' => 'TINYINT', 'constraint' => 2, 'default' => 0],
            'error_msg'  => ['type' => 'TEXT', 'null' => true],
            'sent_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->createTable('email_queue', true);

        // 18. pengaturan
        $this->forge->addField([
            'key'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'value'      => ['type' => 'TEXT', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('key', true);
        $this->forge->createTable('pengaturan', true);
    }

    public function down()
    {
        $tables = [
            'pengaturan', 'email_queue', 'survei_jawaban', 'survei_pertanyaan',
            'lamaran_log', 'lamaran', 'event_peserta', 'keahlian',
            'pengalaman_kerja', 'pencaker', 'lowongan', 'event_perusahaan',
            'perusahaan', 'event_sesi', 'events', 'desa', 'kecamatan', 'users'
        ];
        foreach ($tables as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
