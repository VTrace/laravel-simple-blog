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
    use HasFactory;
    use UsesUuid;

    protected $table = 'posts';
    protected $guarded = ['id'];
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
        'scheduled_at' => 'date',
        'published_at' => 'date',
    ];

    public function scopeScheduleToPublish(Builder $query): Builder
    {
        return $query->where('status', PostStatus::Scheduled)
                    ->where('scheduled_at', '<=', now());
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('status', 'published')->orderBy('published_at', 'desc');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . Str::random(5);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // public function author()
    // {
    //     return $this->hasOne(User::class, 'id', 'author_id');
    // }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
