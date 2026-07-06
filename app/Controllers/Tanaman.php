<?php

namespace App\Controllers;

use App\Models\TanamanModel;

class Tanaman extends BaseController
{
    protected TanamanModel $model;

    public function __construct()
    {
        $this->model = new TanamanModel();
    }

    public function index()
    {
        return view('tanaman/index', ['title' => 'Data Tanaman']);
    }

    public function getData()
    {
        $data = $this->model->getByUser($this->userId());
        foreach ($data as &$row) {
            $row['masa_tanam_fmt'] = $row['masa_tanam'] ? $row['masa_tanam'] . ' hari' : '-';
        }
        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }

    public function show(int $id)
    {
        $row = $this->model->where('user_id', $this->userId())->find($id);
        if (!$row) return $this->errorJson('Tidak ditemukan', 404);
        return $this->jsonResponse(['data' => $row]);
    }

    /**
     * Return map of tanaman_id => satuan for the current user.
     * Used by the panen form to auto-fill satuan when tanaman is selected.
     */
    public function satuanMap()
    {
        $data = $this->model->getByUser($this->userId());
        $map  = [];
        foreach ($data as $row) {
            $map[$row['id']] = $row['satuan'] ?? 'kg';
        }
        return $this->jsonResponse(['data' => $map]);
    }

    public function store()
    {
        $rules = [
            'nama_tanaman' => 'required|min_length[2]|max_length[100]',
            'satuan'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $id = $this->model->insert([
            'user_id'      => $this->userId(),
            'nama_tanaman' => $this->request->getPost('nama_tanaman'),
            'jenis'        => $this->request->getPost('jenis'),
            'varietas'     => $this->request->getPost('varietas'),
            'masa_tanam'   => $this->request->getPost('masa_tanam') ?: null,
            'satuan'       => $this->request->getPost('satuan'),
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return $this->successJson('Tanaman berhasil ditambahkan.', ['id' => $id]);
    }

    public function update(int $id)
    {
        $row = $this->model->where('user_id', $this->userId())->find($id);
        if (!$row) return $this->errorJson('Tidak ditemukan', 404);

        $this->model->update($id, [
            'nama_tanaman' => $this->request->getPost('nama_tanaman'),
            'jenis'        => $this->request->getPost('jenis'),
            'varietas'     => $this->request->getPost('varietas'),
            'masa_tanam'   => $this->request->getPost('masa_tanam') ?: null,
            'satuan'       => $this->request->getPost('satuan'),
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return $this->successJson('Tanaman berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $row = $this->model->where('user_id', $this->userId())->find($id);

        if (!$row) {
            return $this->errorJson('Tidak ditemukan', 404);
        }

        // $used = db_connect()
        //     ->table('panen')
        //     ->where('tanaman_id', $id)
        //     ->countAllResults();

        // if ($used > 0) {
        //     return $this->errorJson(
        //         'Tanaman tidak dapat dihapus karena sudah digunakan dalam pencatatan panen.'
        //     );
        // }

        $this->model->delete($id);

        return $this->successJson('Tanaman berhasil dihapus.');
    }
}