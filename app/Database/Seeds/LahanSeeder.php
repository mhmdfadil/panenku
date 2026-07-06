<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LahanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // User 1
            ['user_id' => 1, 'nama_lahan' => 'Lahan Sawah 1', 'jenis_lahan' => 'Sawah', 'luas' => 0.75, 'lokasi' => 'Blok A, Desa Sukamaju', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_lahan' => 'Lahan Sawah 2', 'jenis_lahan' => 'Sawah', 'luas' => 0.50, 'lokasi' => 'Blok B, Desa Sukamaju', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_lahan' => 'Lahan Kering 1', 'jenis_lahan' => 'Tegalan', 'luas' => 0.60, 'lokasi' => 'Blok C, Desa Sukamaju', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_lahan' => 'Lahan Kering 2', 'jenis_lahan' => 'Ladang', 'luas' => 0.35, 'lokasi' => 'Blok D, Desa Sukamaju', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_lahan' => 'Lahan Kering 3', 'jenis_lahan' => 'Kebun', 'luas' => 0.25, 'lokasi' => 'Blok E, Desa Sukamaju', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            // User 2
            ['user_id' => 2, 'nama_lahan' => 'Sawah Utama', 'jenis_lahan' => 'Sawah', 'luas' => 1.20, 'lokasi' => 'Desa Mekarjaya', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'nama_lahan' => 'Kebun Belakang', 'jenis_lahan' => 'Kebun', 'luas' => 0.40, 'lokasi' => 'Desa Mekarjaya', 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('lahan')->insertBatch($data);
    }
}
