<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasFactory, HasTranslations, SoftDeletes , InteractsWithMedia;

    protected $table = 'services';

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'duration', 
        'requires_payment',
        'booking_type',
        'is_available',
        'sort_order',
        'site_setting_id'
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public $translatable = ['name','description'];

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function offers(): MorphToMany
    {
        return $this->morphToMany(Offer::class, 'offerable');
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'service_branch')->withPivot('is_available')->where('is_visible', true)->withTimestamps();
    }

    public function galleries(): MorphMany
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }

    /**
     * Register media collections for the service
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('service_image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->useDisk('public');
    }

    /**
     * Scope to get only available services
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Check if service requires payment for booking
     */
    public function requiresBookingPayment(): bool
    {
        return $this->booking_type === 'paid_booking';
    }

    /**
     * Get available branches for this service
     */
    public function getAvailableBranches()
    {
        return $this->branches()->wherePivot('is_available', true)->where('is_visible', true);
    }

    /**
     * Check if service is bookable
     */
    public function isBookable(): bool
    {
        return in_array($this->booking_type, ['free_booking', 'paid_booking']);
    }
}
