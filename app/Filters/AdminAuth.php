<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminAuth Filter
 * Melindungi semua route /admin/*
 * Redirect ke halaman login jika belum authenticated
 */
class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->has('user_id')) {
            return redirect()->to(base_url('admin/login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role jika ada argumen (mis. filter:'adminAuth:super_admin')
        if (! empty($arguments)) {
            $role = session('user_role');
            if (! in_array($role, $arguments, true)) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak ada aksi setelah response
    }
}
