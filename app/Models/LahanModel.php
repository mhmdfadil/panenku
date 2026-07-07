<?php

namespace App\Models;

use CodeIgniter\Model;

class LahanModel extends Model
{
    protected $table      = 'lahan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'nama_lahan', 'jenis_lahan', 'luas',
        'lokasi', 'keterangan', 'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama_lahan'  => 'required|min_length[2]|max_length[100]',
        'jenis_lahan' => 'required',
    ];

    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('nama_lahan', 'ASC')->findAll();
    }

    public function getActiveByUser(int $userId): array
    {
        return $this->where('user_id', $userId)->where('status', 'aktif')->orderBy('nama_lahan')->findAll();
    }

    public function getForSelect(int $userId): array
    {
        $rows = $this->where('user_id', $userId)->where('status', 'aktif')->orderBy('nama_lahan')->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['nama_lahan'] . ' (' . $row['jenis_lahan'] . ')';
        }
        return $result;
    }

    public function getTotalLuas(int $userId): float
    {
        $result = $this->selectSum('luas')->where('user_id', $userId)->where('status', 'aktif')->first();
        return (float)($result['luas'] ?? 0);
    }
}
