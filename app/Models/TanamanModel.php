<?php

namespace App\Models;

use CodeIgniter\Model;

class TanamanModel extends Model
{
    protected $table      = 'tanaman';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'nama_tanaman', 'jenis', 'varietas',
        'masa_tanam', 'satuan', 'keterangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama_tanaman' => 'required|min_length[2]|max_length[100]',
        'satuan'       => 'required',
    ];

    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('nama_tanaman', 'ASC')->findAll();
    }

    public function getForSelect(int $userId): array
    {
        $rows = $this->where('user_id', $userId)->orderBy('nama_tanaman')->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['nama_tanaman'] . ($row['varietas'] ? ' (' . $row['varietas'] . ')' : '');
        }
        return $result;
    }
}
