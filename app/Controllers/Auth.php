<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        return view('auth/login', ['title' => 'Login — PanenKu']);
    }

    public function doLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $session = session();
        $session->set([
            'user_id'    => $user['id'],
            'user_nama'  => $user['nama'],
            'user_email' => $user['email'],
            'user_avatar'=> $user['avatar'],
            'user_desa'  => $user['desa'],
            'theme_mode' => $user['theme_mode'],
            'read_mode'  => $user['read_mode'],
            'logged_in'  => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function register()
    {
        return view('auth/register', ['title' => 'Daftar — PanenKu']);
    }

    public function doRegister()
    {
        $rules = [
            'nama'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'konfirmasi_password' => 'required|matches[password]',
        ];

        $messages = [
            'email'    => ['is_unique' => 'Email sudah digunakan.'],
            'konfirmasi_password' => ['matches' => 'Konfirmasi password tidak cocok.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'nama'      => $this->request->getPost('nama'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'desa'      => $this->request->getPost('desa')      ?: null,
            'kecamatan' => $this->request->getPost('kecamatan') ?: null,
            'kabupaten' => $this->request->getPost('kabupaten') ?: null,
            'alamat'    => $this->request->getPost('alamat')    ?: null,
        ]);

        return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil keluar.');
    }
}
