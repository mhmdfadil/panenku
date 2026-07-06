<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTanamanTable extends Migration
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
            'nama_tanaman' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'varietas' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'masa_tanam' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
                'comment'    => 'dalam hari',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'kg',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('tanaman');
    }

    public function down()
    {
        $this->forge->dropTable('tanaman');
    }
}
