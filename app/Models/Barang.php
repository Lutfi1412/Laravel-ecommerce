<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = ['seller_id', 'id', 'name', 'description', 'price', 'stock', 'image'];
}
