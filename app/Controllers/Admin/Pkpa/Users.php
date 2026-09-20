<?php

namespace App\Controllers\Admin\Pkpa;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        $users = $this->userModel->orderBy('id', 'ASC')->findAll();
        return view('admin/pkpa/users', [
            'title' => 'Manajemen User Admin PKPA',
            'users' => $users,
        ]);
    }

    public function save()
    {
        $id       = (int) $this->request->getPost('id');
        $password = $this->request->getPost('password');

        $data = [
            'nama'      => trim($this->request->getPost('nama')),
            'email'     => trim($this->request->getPost('email')),
            'role'      => $this->request->getPost('role'),
            'is_active' => (int) (bool) $this->request->getPost('is_active'),
        ];

        if (! empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($id) {
            $ok = $this->userModel->update($id, $data);
        } else {
            if (empty($password)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Password wajib diisi untuk user baru.', 'csrf_hash' => csrf_hash()]);
            }
            $ok = (bool) $this->userModel->insert($data);
        }

        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'User berhasil disimpan.' : implode(', ', $this->userModel->errors()),
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function delete(int $id)
    {
        // Tidak boleh hapus diri sendiri
        if ($id === (int) session('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri.', 'csrf_hash' => csrf_hash()]);
        }

        $ok = $this->userModel->delete($id);
        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'User berhasil dihapus.' : 'Gagal menghapus user.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}
