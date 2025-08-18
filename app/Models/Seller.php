<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Supaya bisa login
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'sellers'; // Nama tabel di database

    protected $fillable = [
        'name',
        'email',
        'password',
        'saldo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'saldo' => 'decimal:2'
    ];
}
