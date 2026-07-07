<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profil extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user = $this->userModel->find($this->userId());
        return view('profil/index', [
            'title' => 'Profil & Pengaturan',
            'user'  => $user,
        ]);
    }

    public function update()
    {
        $rules = [
            'nama'      => 'required|min_length[3]|max_length[100]',
            'telepon'   => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->update($this->userId(), [
            'nama'       => $this->request->getPost('nama'),
            'telepon'    => $this->request->getPost('telepon'),
            'alamat'     => $this->request->getPost('alamat'),
            'desa'       => $this->request->getPost('desa'),
            'kecamatan'  => $this->request->getPost('kecamatan'),
            'kabupaten'  => $this->request->getPost('kabupaten'),
        ]);

        session()->set('user_nama', $this->request->getPost('nama'));
        session()->set('user_desa', $this->request->getPost('desa'));

        return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePassword()
    {
        $rules = [
            'password_lama'    => 'required',
            'password_baru'    => 'required|min_length[6]',
            'konfirmasi'       => 'required|matches[password_baru]',
        ];

        $messages = [
            'konfirmasi' => ['matches' => 'Konfirmasi password tidak cocok.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->with('tab', 'password');
        }

        $user = $this->userModel->find($this->userId());
        if (!password_verify($this->request->getPost('password_lama'), $user['password'])) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai.')->with('tab', 'password');
        }

        $this->userModel->update($this->userId(), [
            'password' => password_hash($this->request->getPost('password_baru'), PASSWORD_BCRYPT),
        ]);

        return redirect()->to('/profil')->with('success', 'Password berhasil diubah!');
    }

    public function uploadAvatar()
    {
        $file = $this->request->getFile('avatar');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowed)) {
            return redirect()->back()->with('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB.');
        }

        $newName = 'avatar_' . $this->userId() . '_' . time() . '.' . $file->getExtension();
        $file->move(ROOTPATH . 'public/uploads/avatars/', $newName);

        // Delete old avatar
        $user = $this->userModel->find($this->userId());
        if ($user['avatar'] && file_exists(ROOTPATH . 'public/uploads/avatars/' . $user['avatar'])) {
            unlink(ROOTPATH . 'public/uploads/avatars/' . $user['avatar']);
        }

        $this->userModel->update($this->userId(), ['avatar' => $newName]);
        session()->set('user_avatar', $newName);

        return redirect()->to('/profil')->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function saveTheme()
    {
        $theme    = $this->request->getPost('theme_mode') ?? 'system';
        $readMode = (int)($this->request->getPost('read_mode') ?? 0);

        if (!in_array($theme, ['light', 'dark', 'system'])) {
            $theme = 'system';
        }

        $this->userModel->update($this->userId(), [
            'theme_mode' => $theme,
            'read_mode'  => $readMode,
        ]);

        session()->set('theme_mode', $theme);
        session()->set('read_mode', $readMode);

        return $this->jsonResponse(['status' => 'success', 'theme_mode' => $theme, 'read_mode' => $readMode]);
    }
}
