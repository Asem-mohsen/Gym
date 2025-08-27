<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia, SoftDeletes, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_admin',
        'password',
        'password_set_at',
        'gender',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_set_at' => 'datetime',
        ];
    }

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(SiteSetting::class, 'gym_user', 'user_id', 'site_setting_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function site(): HasOne
    {
        return $this->hasOne(SiteSetting::class, 'owner_id');
    }

    public function managedBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'manager_id');
    }

    public function trainerInformation(): HasOne
    {
        return $this->hasOne(TrainerInformation::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function sentInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'inviter_id');
    }

    public function usedInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'used_by_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function commentLikes(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'comment_likes');
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    /**
     * Check if user has set their password
     */
    public function hasSetPassword(): bool
    {
        return !is_null($this->password_set_at) && !is_null($this->password);
    }

    public function blogPostShares(): HasMany
    {
        return $this->hasMany(BlogPostShare::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the current site for this user (either as owner or staff member)
     */
    public function getCurrentSite(): ?SiteSetting
    {
        $site = $this->site;
        
        if (!$site && $this->gyms()->exists()) {
            $site = $this->gyms()->first();
        }
        
        return $site;
    }

    /**
     * Check if user is a gym owner
     */
    public function isGymOwner(): bool
    {
        return $this->site()->exists();
    }

    /**
     * Check if user is staff member (not owner)
     */
    public function isStaffMember(): bool
    {
        return !$this->isGymOwner() && $this->gyms()->exists();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'created_by_id');
    }

    public function branchScoreHistory(): HasMany
    {
        return $this->hasMany(BranchScoreHistory::class, 'changed_by_id');
    }

    public function branchScoreReviewRequests(): HasMany
    {
        return $this->hasMany(BranchScoreReviewRequest::class, 'requested_by_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'class_trainer', 'trainer_id', 'class_id');
    }

    public function getSiteSettingIdAttribute()
    {
        return $this->site?->id;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === 1 || $this->hasRole('admin');
    }

    /**
     * Get the user's image URL based on gender and media availability
     *
     * @return string
     */
    public function getUserImageAttribute(): string
    {
        $userImage = $this->getFirstMediaUrl('user_images');
        
        if ($userImage) {
            return $userImage;
        }
        
        if ($this->gender === 'male') {
            return asset('assets/admin/img/boy-avatar.jpg');
        }
        
        return asset('assets/admin/img/women-avatar.webp');
    }

    /**
     * Check if user has permission to access financial data
     */
    public function canAccessFinancials(): bool
    {
        return $this->isAdmin() || $this->hasPermissionTo('view_financials');
    }

    /**
     * Check if user has permission to manage site settings
     */
    public function canManageSiteSettings(): bool
    {
        return $this->isAdmin() || $this->hasPermissionTo('manage_site_settings');
    }

    /**
     * Check if user has permission to manage branches
     */
    public function canManageBranches(): bool
    {
        return $this->isAdmin() || $this->hasPermissionTo('manage_branches');
    }

    /**
     * Check if user has permission to access deactivation page
     */
    public function canAccessDeactivation(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user has permission to manage score system
     */
    public function canManageScores(): bool
    {
        return $this->isAdmin() || $this->hasPermissionTo('manage_scores');
    }
}
