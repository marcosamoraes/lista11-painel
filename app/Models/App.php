<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'active',
    ];

    /**
     * Get the companies for the app.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_apps');
    }
}
