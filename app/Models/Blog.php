<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    /**
     * Get the user that owns the blog post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optional: Define media collections if you want specific conversions or settings
    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('images'); // A general collection for blog content images
    //     // $this->addMediaCollection('featured_image')->singleFile(); // For a single featured image
    // }

    // Optional: Define media conversions
    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //           ->width(368)
    //           ->height(232)
    //           ->sharpen(10);
    // }
}
