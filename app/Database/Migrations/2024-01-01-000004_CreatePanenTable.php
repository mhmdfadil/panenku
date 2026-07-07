<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePanenTable extends Migration
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
            'tanaman_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'lahan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_panen' => [
                'type' => 'DATE',
            ],
            'jumlah_panen' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'kg',
            ],
            'harga_per_kg' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,0',
                'default'    => 0,
            ],
            'total_nilai' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,0',
                'default'    => 0,
            ],
            'kualitas' => [
                'type'       => 'ENUM',
                'constraint' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'],
                'default'    => 'Baik',
            ],
            'cuaca' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->addForeignKey('tanaman_id', 'tanaman', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('lahan_id', 'lahan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('panen');
    }

    public function down()
    {
        $this->forge->dropTable('panen');
    }
}
