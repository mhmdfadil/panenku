<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLahanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_lahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'jenis_lahan' => [
                'type'       => 'ENUM',
                'constraint' => ['Sawah', 'Ladang', 'Kebun', 'Tegalan', 'Lainnya'],
                'default'    => 'Sawah',
            ],
            'luas' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
                'comment'    => 'dalam hektar',
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'tidak aktif'],
                'default'    => 'aktif',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lahan');
    }

    public function down()
    {
        $this->forge->dropTable('lahan');
    }
}
