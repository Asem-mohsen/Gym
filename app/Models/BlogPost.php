<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogPost extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(BlogPostShare::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->approved()->parentComments();
    }

    public function getFacebookSharesCountAttribute(): int
    {
        return $this->shares()->byPlatform('facebook')->count();
    }

    public function getTwitterSharesCountAttribute(): int
    {
        return $this->shares()->byPlatform('twitter')->count();
    }

    public function getEmailSharesCountAttribute(): int
    {
        return $this->shares()->byPlatform('email')->count();
    }
}
