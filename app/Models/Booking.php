<?php

namespace App\Models;

use App\Domain\Billing\Contracts\Purchasable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\PurchasableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model implements Purchasable
{
    use HasFactory, SoftDeletes, PurchasableTrait;
    
    protected $guarded = [];
    
    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function pricing(): BelongsTo
    {
        return $this->belongsTo(ClassPricing::class, 'pricing_id');
    }


    /**
     * --------------------------------------------------------------------------------------------------------------------------------------------------------
     * Purchasable methods
     * --------------------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function getTitle(): string
    {
        return $this->bookable->title ?? 'Booking';
    }

    public function getAmount(): int
    {
        return (int) ($this->amount);
    }

    public function getCurrency(): string
    {
        return $this->pricing->currency ?? 'EGP';
    }

    public function getCustomerEmail(): ?string
    {
        return $this->user->email ?? null;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->user->phone ?? null;
    }

    public function getMetadata(): array
    {
        return [
            'booking_id' => $this->id,
            'bookable_type' => $this->bookable_type,
            'bookable_id' => $this->bookable_id,
            'pricing_id' => $this->pricing_id,
            'booking_date' => $this->booking_date,
        ];
    }
}
