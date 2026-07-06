<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'Budi Santoso',
                'email'      => 'budi@panenku.id',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'telepon'    => '081234567890',
                'alamat'     => 'Jl. Sawah Indah No. 12',
                'desa'       => 'Desa Sukamaju',
                'kecamatan'  => 'Kec. Ciawi',
                'kabupaten'  => 'Kab. Bogor',
                'theme_mode' => 'system',
                'read_mode'  => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Sari Dewi',
                'email'      => 'sari@panenku.id',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'telepon'    => '082345678901',
                'alamat'     => 'Jl. Pertanian No. 5',
                'desa'       => 'Desa Mekarjaya',
                'kecamatan'  => 'Kec. Jonggol',
                'kabupaten'  => 'Kab. Bogor',
                'theme_mode' => 'light',
                'read_mode'  => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
