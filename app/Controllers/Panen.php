<?php

namespace App\Controllers;

use App\Models\PanenModel;
use App\Models\TanamanModel;
use App\Models\LahanModel;

class Panen extends BaseController
{
    protected PanenModel   $panenModel;
    protected TanamanModel $tanamanModel;
    protected LahanModel   $lahanModel;

    public function __construct()
    {
        $this->panenModel   = new PanenModel();
        $this->tanamanModel = new TanamanModel();
        $this->lahanModel   = new LahanModel();
    }

    public function index()
    {
        $userId = $this->userId();
        return view('panen/index', [
            'title'    => 'Pencatatan Panen',
            'tanaman'  => $this->tanamanModel->getForSelect($userId),
            'lahan'    => $this->lahanModel->getForSelect($userId),
        ]);
    }

    // AG Grid data endpoint
    public function getData()
    {
        $userId  = $this->userId();
        $filters = [
            'tanaman_id' => $this->request->getGet('tanaman_id'),
            'lahan_id'   => $this->request->getGet('lahan_id'),
            'kualitas'   => $this->request->getGet('kualitas'),
            'dari'       => $this->request->getGet('dari'),
            'sampai'     => $this->request->getGet('sampai'),
            'search'     => $this->request->getGet('search'),
        ];

        $data = $this->panenModel->getWithRelations($userId, $filters);

        // Format for AG Grid
        foreach ($data as &$row) {
            $row['tanggal_panen_fmt']  = date('d M Y', strtotime($row['tanggal_panen']));
            $row['jumlah_panen_fmt']   = number_format($row['jumlah_panen'], 0, ',', '.') . ' ' . $row['satuan'];
            $row['harga_per_kg_fmt']   = 'Rp ' . number_format($row['harga_per_kg'], 0, ',', '.');
            $row['total_nilai_fmt']    = 'Rp ' . number_format($row['total_nilai'], 0, ',', '.');
            $row['komoditas']          = $row['nama_tanaman'] . ($row['varietas'] ? ' - ' . $row['varietas'] : '');
        }

        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }

    public function create()
    {
        $userId = $this->userId();
        return view('panen/form', [
            'title'   => 'Catat Panen Baru',
            'tanaman' => $this->tanamanModel->getForSelect($userId),
            'lahan'   => $this->lahanModel->getForSelect($userId),
            'panen'   => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'tanaman_id'    => 'required|is_natural_no_zero',
            'lahan_id'      => 'required|is_natural_no_zero',
            'tanggal_panen' => 'required',
            'jumlah_panen'  => 'required|greater_than[0]',
            'harga_per_kg'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jumlah     = (float)$this->request->getPost('jumlah_panen');
        $harga      = (float)str_replace(['.', ','], ['', '.'], $this->request->getPost('harga_per_kg'));
        $totalNilai = $jumlah * $harga;

        // Handle foto upload
        $foto = null;
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/panen/', $newName);
            $foto = $newName;
        }

        $this->panenModel->insert([
            'user_id'       => $this->userId(),
            'tanaman_id'    => $this->request->getPost('tanaman_id'),
            'lahan_id'      => $this->request->getPost('lahan_id'),
            'tanggal_panen' => $this->request->getPost('tanggal_panen'),
            'jumlah_panen'  => $jumlah,
            'satuan'        => $this->request->getPost('satuan') ?: 'kg',
            'harga_per_kg'  => $harga,
            'total_nilai'   => $totalNilai,
            'kualitas'      => $this->request->getPost('kualitas'),
            'cuaca'         => $this->request->getPost('cuaca'),
            'catatan'       => $this->request->getPost('catatan'),
            'foto'          => $foto,
        ]);

        return redirect()->to('/panen')->with('success', 'Data panen berhasil disimpan!');
    }

    public function show(int $id)
    {
        $data = $this->panenModel->getWithRelations($this->userId(), []);
        $panen = array_filter($data, fn($p) => $p['id'] == $id);
        if (empty($panen)) {
            return $this->errorJson('Data tidak ditemukan', 404);
        }
        return $this->jsonResponse(['data' => array_values($panen)[0]]);
    }

    public function edit(int $id)
    {
        $userId = $this->userId();
        $panen  = $this->panenModel->where('user_id', $userId)->find($id);
        if (!$panen) {
            return redirect()->to('/panen')->with('error', 'Data tidak ditemukan.');
        }
        return view('panen/form', [
            'title'   => 'Edit Data Panen',
            'tanaman' => $this->tanamanModel->getForSelect($userId),
            'lahan'   => $this->lahanModel->getForSelect($userId),
            'panen'   => $panen,
        ]);
    }

    public function update(int $id)
    {
        $userId = $this->userId();
        $panen  = $this->panenModel->where('user_id', $userId)->find($id);
        if (!$panen) {
            return redirect()->to('/panen')->with('error', 'Data tidak ditemukan.');
        }

        $jumlah     = (float)$this->request->getPost('jumlah_panen');
        $harga      = (float)str_replace(['.', ','], ['', '.'], $this->request->getPost('harga_per_kg'));
        $totalNilai = $jumlah * $harga;

        $updateData = [
            'tanaman_id'    => $this->request->getPost('tanaman_id'),
            'lahan_id'      => $this->request->getPost('lahan_id'),
            'tanggal_panen' => $this->request->getPost('tanggal_panen'),
            'jumlah_panen'  => $jumlah,
            'satuan'        => $this->request->getPost('satuan') ?: 'kg',
            'harga_per_kg'  => $harga,
            'total_nilai'   => $totalNilai,
            'kualitas'      => $this->request->getPost('kualitas'),
            'cuaca'         => $this->request->getPost('cuaca'),
            'catatan'       => $this->request->getPost('catatan'),
        ];

        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/panen/', $newName);
            $updateData['foto'] = $newName;
        }

        $this->panenModel->update($id, $updateData);
        return redirect()->to('/panen')->with('success', 'Data panen berhasil diperbarui!');
    }

    public function delete(int $id)
    {
        $panen = $this->panenModel->where('user_id', $this->userId())->find($id);
        if (!$panen) {
            return $this->errorJson('Data tidak ditemukan', 404);
        }
        $this->panenModel->delete($id);
        return $this->successJson('Data panen berhasil dihapus.');
    }
}
