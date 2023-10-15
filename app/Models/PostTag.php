<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'tag_id',
    ];

    /**
     * Get the post that owns the post tag.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the tag that owns the post tag.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
