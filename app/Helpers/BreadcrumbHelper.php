<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Role;
use App\Models\Batch;

class BreadcrumbHelper
{
    public static function generate()
    {
        $segments = request()->segments();
        $breadcrumbs = [];
        $url = '';

        // Daftar label custom
        $names = [
            'dashboard' => 'Dashboard',
            'role'      => 'Role Management',
            'user'      => 'User Management',
            'batch'     => 'Batch Management',
            'add'       => 'Add Data',
            'edit'      => 'Edit Data',
            'store'     => 'Save',
            'delete'    => 'Delete',
            'update'    => 'Update',
        ];

        foreach ($segments as $key => $segment) {
            $url .= '/' . $segment;

            // Skip segment yang tidak ingin ditampilkan
            if ($segment === 'panel') continue;

            // Default label
            $label = $names[$segment] ?? ucfirst(str_replace('-', ' ', $segment));

            // Jika segment berupa ID (angka atau UUID)
            if (is_numeric($segment) || preg_match('/^[0-9a-fA-F-]{36}$/', $segment)) {
                $prevSegment = $segments[$key - 1] ?? null;
                $data = null;

                switch ($prevSegment) {
                    case 'user':
                        $data = User::find($segment);
                        break;
                    case 'role':
                        $data = Role::find($segment);
                        break;
                    case 'batch':
                        $data = Batch::find($segment);
                        break;
                }

                // Jika tidak ada data, skip segment (tidak tampilkan UUID)
                if (!$data) {
                    continue;
                }

                // Ambil nama dari data
                $label = $data->name ?? $label;
            }

            // Tambahkan ke breadcrumb
            if ($key === array_key_last($segments)) {
                $breadcrumbs[] = ['name' => $label, 'url' => null];
            } else {
                $breadcrumbs[] = ['name' => $label, 'url' => url($url)];
            }
        }

        return $breadcrumbs;
    }
}
