<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        if (session()->has('user_id')) {
            return redirect()->to(base_url('admin/pkpa'));
        }

        return view('admin/auth/login', [
            'title' => 'Login Admin — PKPA PERADI Tangerang Raya',
        ]);
    }

    public function doLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyLogin($email, $password);

        if (! $user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah, atau akun tidak aktif.');
        }

        // Simpan sesi
        session()->set([
            'user_id'    => $user['id'],
            'user_nama'  => $user['nama'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'logged_in'  => true,
        ]);

        $this->userModel->touchLastLogin($user['id']);

        return redirect()->to(base_url('admin/pkpa'))
            ->with('success', 'Selamat datang, ' . $user['nama'] . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'))
            ->with('success', 'Anda telah logout.');
    }
}
