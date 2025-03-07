<?php

namespace App\Models;

use App\Traits\UsesUuid;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'body',
        'status',
        'slug',
        'author_id',
        'scheduled_at',
        'published_at',
    ];

    protected $casts = [
        'status' => PostStatus::class,
        'scheduled_at' => 'immutable_date', // Prefer immutable date type
        'published_at' => 'immutable_date',
    ];

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug only when creating
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . Str::random(5);
            }
        });
    }

    /**
     * Scope: Select only posts that are scheduled for publishing.
     */
    public function scopeScheduleToPublish(Builder $query): Builder
    {
        return $query->where('status', PostStatus::Scheduled)
                    ->where('scheduled_at', '<=', now());
    }

    /**
     * Scope: Select only published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
                    ->where('status', PostStatus::Published)
                    ->latest('published_at');
    }

    /**
     * Get the route model binding key.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relationship: A post belongs to an author.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id')->withDefault();
    }
}
