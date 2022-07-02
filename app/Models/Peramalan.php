<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peramalan extends Model
{
    use HasFactory;

    protected $table = 'peramalan';
    protected $fillable = [
        'single_id', 'double_id', 'triple_id', 'tgl'
    ];
}
