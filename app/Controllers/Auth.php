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

    public function lupaPassword()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }

        $email = trim((string) ($this->request->getGet('email') ?: old('email')));
        $detectedRole = null;

        if ($email !== '') {
            $user = $this->db->table('users')
                ->where('email', $email)
                ->whereIn('role', ['pencaker', 'perusahaan'])
                ->get()
                ->getRowArray();

            if ($user) {
                $detectedRole = $user['role'];
            }
        }

        return $this->renderView('publik/lupa_password', [
            'title'        => 'Lupa Kata Sandi',
            'email'        => $email,
            'detectedRole' => $detectedRole,
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '62')) {
            return '0' . substr($digits, 2);
        }
        if (str_starts_with($digits, '8')) {
            return '0' . $digits;
        }
        return $digits;
    }

    public function attemptLupaPassword()
    {
        $step  = (string) $this->request->getPost('step');
        $email = trim((string) $this->request->getPost('email'));

        $user = $this->db->table('users')
            ->where('email', $email)
            ->whereIn('role', ['pencaker', 'perusahaan'])
            ->get()
            ->getRowArray();

        if (! $user) {
            return redirect()->to('/lupa-password')
                ->withInput()
                ->with('error', 'Alamat email tidak ditemukan atau tidak terdaftar sebagai Pencari Kerja / Perusahaan.');
        }

        if (empty($user['is_active'])) {
            return redirect()->to('/lupa-password')
                ->withInput()
                ->with('error', 'Akun Anda sedang dinonaktifkan oleh Administrator.');
        }

        // Step 1: Just detect role and show verification fields
        if ($step === 'cek_email') {
            return redirect()->to('/lupa-password?email=' . urlencode($email));
        }

        // Step 2: Verify identity and update password
        $passwordBaru = (string) $this->request->getPost('password_baru');
        $konfirmasi   = (string) $this->request->getPost('konfirmasi_password');
        $hpInput      = $this->normalizePhone((string) $this->request->getPost('hp'));

        if (strlen($passwordBaru) < 6) {
            return redirect()->to('/lupa-password?email=' . urlencode($email))
                ->withInput()
                ->with('error', 'Kata sandi baru minimal 6 karakter.');
        }

        if ($passwordBaru !== $konfirmasi) {
            return redirect()->to('/lupa-password?email=' . urlencode($email))
                ->withInput()
                ->with('error', 'Konfirmasi kata sandi baru tidak cocok.');
        }

        if ($user['role'] === 'pencaker') {
            $nikInput = trim((string) $this->request->getPost('nik'));
            $pck = $this->db->table('pencaker')->where('user_id', $user['id'])->get()->getRowArray();

            if (
                ! $pck ||
                $nikInput === '' ||
                $hpInput === '' ||
                $nikInput !== trim((string) $pck['nik']) ||
                $hpInput !== $this->normalizePhone((string) $pck['hp'])
            ) {
                return redirect()->to('/lupa-password?email=' . urlencode($email))
                    ->withInput()
                    ->with('error', 'Verifikasi gagal. NIK atau Nomor HP tidak sesuai dengan data akun Anda.');
            }
        } elseif ($user['role'] === 'perusahaan') {
            $boothInput = strtoupper(trim((string) $this->request->getPost('no_booth')));
            $comp = $this->db->table('perusahaan')->where('user_id', $user['id'])->get()->getRowArray();

            $epBuilder = $this->db->table('event_perusahaan')->where('perusahaan_id', $comp['id'] ?? 0);
            if ($this->activeEvent) {
                $epBuilder->where('event_id', $this->activeEvent['id']);
            }
            $ep = $epBuilder->get()->getRowArray();

            $hpHrdDb  = $this->normalizePhone((string) ($comp['hp_hrd'] ?? ''));
            $telpDb   = $this->normalizePhone((string) ($comp['telp'] ?? ''));
            $boothDb  = strtoupper(trim((string) ($ep['no_booth'] ?? '')));

            $hpMatch = $hpInput !== '' && ($hpInput === $hpHrdDb || $hpInput === $telpDb);
            $boothMatch = $boothInput !== '' && $boothInput === $boothDb;

            if (! $comp || ! $boothMatch || ! $hpMatch) {
                return redirect()->to('/lupa-password?email=' . urlencode($email))
                    ->withInput()
                    ->with('error', 'Verifikasi gagal. Nomor Booth atau Nomor HP tidak sesuai dengan data perusahaan Anda.');
            }
        }

        $this->db->table('users')->where('id', $user['id'])->update([
            'password'   => password_hash($passwordBaru, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
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
