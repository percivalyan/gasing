<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional jika sesuai konvensi)
     */
    protected $table = 'footers';

    /**
     * Field yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'phone',
        'email',
        'address_street',
        'address_post_code',
    ];

    /**
     * Cast ke tipe data yang sesuai (opsional)
     */
    protected $casts = [
        'phone' => 'string',
        'email' => 'string',
        'address_street' => 'string',
        'address_post_code' => 'string',
    ];
}

