<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }
        return $this->renderView('publik/login', ['title' => 'Masuk Akun']);
    }

    public function attemptLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        // Simple brute-force rate limit per session
        $attempts = (int) session()->get('login_attempts');
        $lockUntil = (int) session()->get('login_lock_until');
        if ($lockUntil > time()) {
            $wait = $lockUntil - time();
            return redirect()->back()->withInput()->with('error', "Terlalu banyak percobaan gagal. Silakan tunggu {$wait} detik.");
        }

        $user = $this->db->table('users')->where('email', $email)->get()->getRowArray();

        if (! $user || ! password_verify($password, $user['password'])) {
            $attempts++;
            session()->set('login_attempts', $attempts);
            if ($attempts >= 5) {
                session()->set('login_lock_until', time() + 60);
                session()->set('login_attempts', 0);
            }
            return redirect()->back()->withInput()->with('error', 'Email atau kata sandi salah.');
        }

        if (empty($user['is_active'])) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan oleh Administrator.');
        }

        session()->remove(['login_attempts', 'login_lock_until']);
        session()->regenerate();

        $this->db->table('users')->where('id', $user['id'])->update(['last_login' => date('Y-m-d H:i:s')]);

        session()->set([
            'user_id'   => $user['id'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true,
        ]);

        $redirectUrl = session()->get('redirect_after_login');
        if ($redirectUrl) {
            session()->remove('redirect_after_login');
            return redirect()->to($redirectUrl);
        }

        return $this->redirectByRole($user['role']);
    }

    public function daftar()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }
        return $this->renderView('publik/daftar', ['title' => 'Pendaftaran Akun Pencari Kerja']);
    }

    public function attemptDaftar()
    {
        $rules = [
            'nik'        => 'required|exact_length[16]|numeric|is_unique[pencaker.nik]',
            'nama'       => 'required|min_length[3]|max_length[150]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'hp'         => 'required|min_length[9]|max_length[25]',
            'password'   => 'required|min_length[6]',
            'setuju_pdp' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $now  = date('Y-m-d H:i:s');
        $nama = trim((string) $this->request->getPost('nama'));
        $email= trim((string) $this->request->getPost('email'));
        $nik  = trim((string) $this->request->getPost('nik'));
        $hp   = trim((string) $this->request->getPost('hp'));
        $jk   = $this->request->getPost('jk') === 'P' ? 'P' : 'L';
        $pend = (string) $this->request->getPost('pendidikan');

        $this->db->transStart();

        $this->db->table('users')->insert([
            'nama'       => $nama,
            'email'      => $email,
            'password'   => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'       => 'pencaker',
            'is_active'  => 1,
            'last_login' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $userId = $this->db->insertID();

        $this->db->table('pencaker')->insert([
            'user_id'        => $userId,
            'nik'            => $nik,
            'nama'           => $nama,
            'jk'             => $jk,
            'hp'             => $hp,
            'pendidikan'     => $pend ?: 'SMK',
            'profil_lengkap' => 0,
            'setuju_pdp'     => 1,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        $this->db->transComplete();

        session()->regenerate();
        session()->set([
            'user_id'   => $userId,
            'nama'      => $nama,
            'email'     => $email,
            'role'      => 'pencaker',
            'logged_in' => true,
        ]);

        return redirect()->to('/pencaker/profil')->with('success', 'Akun berhasil dibuat! Silakan lengkapi profil, unggah pas foto, dan isi link berkas gabungan PDF Anda.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah keluar dari sistem.');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'      => redirect()->to('/admin'),
            'perusahaan' => redirect()->to('/perusahaan'),
            'petugas'    => redirect()->to('/petugas'),
            'pimpinan'   => redirect()->to('/pimpinan'),
            default      => redirect()->to('/pencaker'),
        };
    }
}
