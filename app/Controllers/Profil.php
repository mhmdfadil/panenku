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

}
