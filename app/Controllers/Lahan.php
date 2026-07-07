<?php

namespace App\Controllers;

use App\Models\LahanModel;

class Lahan extends BaseController
{
    protected LahanModel $model;

    public function __construct()
    {
        $this->model = new LahanModel();
    }

    public function index()
    {
        return view('lahan/index', ['title' => 'Data Lahan']);
    }

    public function getData()
    {
        $data = $this->model->getByUser($this->userId());
        foreach ($data as &$row) {
            $row['luas_fmt'] = $row['luas'] ? number_format($row['luas'], 2) . ' ha' : '-';
        }
        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }

    public function show(int $id)
    {
        $row = $this->model->where('user_id', $this->userId())->find($id);
        if (!$row) return $this->errorJson('Tidak ditemukan', 404);
        return $this->jsonResponse(['data' => $row]);
    }

    public function store()
    {
        $rules = [
            'nama_lahan'  => 'required|min_length[2]|max_length[100]',
            'jenis_lahan' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $id = $this->model->insert([
            'user_id'     => $this->userId(),
            'nama_lahan'  => $this->request->getPost('nama_lahan'),
            'jenis_lahan' => $this->request->getPost('jenis_lahan'),
            'luas'        => $this->request->getPost('luas') ?: null,
            'lokasi'      => $this->request->getPost('lokasi'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'status'      => $this->request->getPost('status') ?: 'aktif',
        ]);

        return $this->successJson('Lahan berhasil ditambahkan.', ['id' => $id]);
    }

    public function update(int $id)
    {
        $row = $this->model->where('user_id', $this->userId())->find($id);
        if (!$row) return $this->errorJson('Tidak ditemukan', 404);

        $this->model->update($id, [
            'nama_lahan'  => $this->request->getPost('nama_lahan'),
            'jenis_lahan' => $this->request->getPost('jenis_lahan'),
            'luas'        => $this->request->getPost('luas') ?: null,
            'lokasi'      => $this->request->getPost('lokasi'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'status'      => $this->request->getPost('status') ?: 'aktif',
        ]);

        return $this->successJson('Lahan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $row = $this->model->where('user_id', $this->userId())->find($id);

        if (!$row) {
            return $this->errorJson('Tidak ditemukan', 404);
        }

        $used = db_connect()
            ->table('panen')
            ->where('lahan_id', $id)
            ->countAllResults();

        if ($used > 0) {
            return $this->errorJson(
                'Lahan tidak dapat dihapus karena sudah digunakan dalam pencatatan panen.'
            );
        }

        $this->model->delete($id);

        return $this->successJson('Lahan berhasil dihapus.');
    }
}
