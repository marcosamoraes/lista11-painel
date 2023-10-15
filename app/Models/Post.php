<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'active',
        'visits',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });

        self::updating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/' . $this->image) : null);
    }

    /**
     * Get the tags for the post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
}
