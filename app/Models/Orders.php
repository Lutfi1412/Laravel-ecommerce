<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = ['customer_id', 'barang_id', 'status', 'jumlah', 'created_at'];
}
