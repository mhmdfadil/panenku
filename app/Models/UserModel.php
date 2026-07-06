<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nama', 'email', 'password', 'telepon', 'alamat',
        'avatar', 'desa', 'kecamatan', 'kabupaten',
        'theme_mode', 'read_mode',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama'  => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
    ];

    protected $validationMessages = [
        'nama'  => ['required' => 'Nama wajib diisi.'],
        'email' => ['required' => 'Email wajib diisi.', 'valid_email' => 'Format email tidak valid.'],
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
