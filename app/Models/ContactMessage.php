<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email',
        'service_type',
        'message',
        'ip_address',
    ];
}
