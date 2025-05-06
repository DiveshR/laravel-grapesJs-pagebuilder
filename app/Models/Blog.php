<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('blog_images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml']);
             // You could also define that this collection should generate the thumb conversion by default
             // ->registerMediaConversions(function (Media $media = null) {
             //     $this->addMediaConversion('thumb')
             //         ->width(150)
             //         ->height(150);
             // });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(150) // Example width
              ->height(150) // Example height
              ->sharpen(5) // Example sharpen value
              ->nonQueued(); // Process immediately
    }
}
