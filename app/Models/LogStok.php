<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    protected $fillable = [
        'ProdukId',
        'JumlahProduk',
        'Users_Id',

    ];
}
