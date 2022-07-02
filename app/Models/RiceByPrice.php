<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiceByPrice extends Model
{
    use HasFactory;

    protected $fillable = ['rice_type_id', 'year', 'month', 'price', 'user_id'];

    public function type()
    {
        return $this->belongsTo(RiceType::class, 'rice_type_id');
    }
}
