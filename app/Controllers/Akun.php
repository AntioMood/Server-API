<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Modelakun;
use App\Controllers\BaseController;

class Akun extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $modelAkun = new Modelakun();
        $data = $modelAkun->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];
        return $this->respond($response, 200);
    }

    public function show($cari = null)
    {
        $modelAkun = new Modelakun();
        $data = $modelAkun->orLike('username', $cari)
            ->orLike('password', $cari)->get()->getResult();
        if (count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        } else if (count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        } else {
            return $this->failNotFound('maaf data ' . $cari .
                ' tidak ditemukan');
        }
    }

    public function create()
    {
        $modelAkun = new Modelakun();
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        $tgl_lahir = $this->request->getPost("birth_date");
        $no_telp = $this->request->getPost("no_telp");
        $email = $this->request->getPost("email");
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'username' => [
                'rules' => 'is_unique[akun.username]',
                'label' => 'Username Akun',
                'errors' => [
                    'is_unique' => "Akun {field} sudah ada"
                ]
            ]
        ]);
        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("username"),
            ];
            return $this->respond($response, 404);
        } else {
            $modelAkun->insert([
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'no_telp' => $no_telp,
                'birth_date' => $tgl_lahir,
            ]);
            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Register Berhasil"
            ];
            return $this->respond($response, 201);
        }
    }

    public function update($username = null)
    {
        $model = new Modelakun();
        $data = [
            'username' => $this->request->getVar("username"),
            'password' => $this->request->getVar("password"),
            'email' => $this->request->getVar("email"),
            'no_telp' => $this->request->getVar("no_telp"),
            'birth_date' => $this->request->getVar("birth_date"),
        ];
        $data = $this->request->getRawInput();
        $model->update($username, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Akun $username berhasil diupdate"
        ];
        return $this->respond($response);
    }

    public function delete($username)
    {
        $modelAkun = new Modelakun();
        $cekData = $modelAkun->find($username);
        if ($cekData) {
            $modelAkun->delete($username);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
