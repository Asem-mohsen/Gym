<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAttempt extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}