<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triple extends Model
{
    use HasFactory;

    protected $table = 'triple';
    protected $fillable = [
        'nilai_aktual', 'ramal', 'PE', 'MAPE', 'MSE', 'RMSE', 'alfa'
    ];
}
