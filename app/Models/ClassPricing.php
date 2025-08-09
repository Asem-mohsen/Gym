<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPricing extends Model
{
    use HasFactory;

    protected $table = 'class_pricings';

    protected $fillable = [
        'class_id',
        'price',
        'duration',
    ];

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
} 