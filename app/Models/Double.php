<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Double extends Model
{
    use HasFactory;

    protected $table = 'double';
    protected $fillable = [
        'nilai_aktual', 'ramal', 'PE', 'MAPE', 'MSE', 'RMSE', 'alfa'
    ];
}
