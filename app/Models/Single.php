<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Single extends Model
{
    use HasFactory;

    protected $table = 'single';
    protected $fillable = [
        'rice_type_id', 'nilai_aktual', 'ramal', 'PE', 'MAPE', 'MSE', 'RMSE', 'alfa'
    ];

    public function type()
    {
        return $this->belongsTo(RiceType::class, 'rice_type_id');
    }
}
