<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Session extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'sessions';

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'action',
        'description',
        'payload',
        'last_activity',
    ];

    public function user()
    {
        // relasi ke model User dengan UUID
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
