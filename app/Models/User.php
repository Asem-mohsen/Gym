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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements HasMedia, FilamentUser
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
        'last_visit_at',
        'gender',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = ['has_set_password'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_set_at' => 'datetime',
            'last_visit_at' => 'datetime',
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
    
    public function getHasSetPasswordAttribute(): bool
    {
        return $this->hasSetPassword();
    }

    public function blogPostShares(): HasMany
    {
        return $this->hasMany(BlogPostShare::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function coachingSessions(): HasMany
    {
        return $this->hasMany(CoachingSession::class, 'coach_id');
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

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'manager_id');
    }

    public function assignedBranches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'user_branch', 'user_id', 'branch_id');
    }

    public function getSiteSettingIdAttribute()
    {
        return $this->site?->id;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === 1 || $this->hasAnyRole(['admin', 'management','sales','trainer']);
    }

    /**
     * Get user initials from name
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->name);
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Check if user has a specific gym permission
     */
    public function hasGymPermission(string $permissionName, int $siteSettingId): bool
    {
        return $this->permissions()
            ->where('name', $permissionName)
            ->wherePivot('site_setting_id', $siteSettingId)
            ->exists();
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('master_admin');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UserPhoto::class);
    }

    /**
     * Get public user photos
     */
    public function publicPhotos(): HasMany
    {
        return $this->hasMany(UserPhoto::class)->public()->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
