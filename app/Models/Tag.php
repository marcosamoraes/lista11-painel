<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the companies for the tag.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_tags');
    }

    /**
     * Get the posts for the tag.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
