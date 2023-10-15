<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'app_id',
        'url',
    ];

    /**
     * Get the company that owns the company app.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the app that owns the company app.
     */
    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
