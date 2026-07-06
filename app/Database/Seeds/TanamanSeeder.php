<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TanamanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // User 1 (Budi)
            ['user_id' => 1, 'nama_tanaman' => 'Padi', 'jenis' => 'Serealia', 'varietas' => 'IR64', 'masa_tanam' => 120, 'satuan' => 'kg', 'keterangan' => 'Padi sawah varietas IR64', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_tanaman' => 'Jagung', 'jenis' => 'Serealia', 'varietas' => 'Pioneer 27', 'masa_tanam' => 90, 'satuan' => 'kg', 'keterangan' => 'Jagung hibrida', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_tanaman' => 'Cabai', 'jenis' => 'Hortikultura', 'varietas' => 'Merah Besar', 'masa_tanam' => 75, 'satuan' => 'kg', 'keterangan' => 'Cabai merah keriting', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_tanaman' => 'Kedelai', 'jenis' => 'Kacang-kacangan', 'varietas' => 'Anjasmoro', 'masa_tanam' => 85, 'satuan' => 'kg', 'keterangan' => 'Kedelai lokal', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'nama_tanaman' => 'Tomat', 'jenis' => 'Hortikultura', 'varietas' => 'Servo', 'masa_tanam' => 70, 'satuan' => 'kg', 'keterangan' => 'Tomat buah', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            // User 2 (Sari)
            ['user_id' => 2, 'nama_tanaman' => 'Padi', 'jenis' => 'Serealia', 'varietas' => 'Ciherang', 'masa_tanam' => 116, 'satuan' => 'kg', 'keterangan' => 'Padi varietas Ciherang', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'nama_tanaman' => 'Singkong', 'jenis' => 'Umbi-umbian', 'varietas' => 'UJ5', 'masa_tanam' => 240, 'satuan' => 'kg', 'keterangan' => 'Singkong produksi tinggi', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('tanaman')->insertBatch($data);
    }
}
