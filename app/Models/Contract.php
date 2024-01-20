<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contractor',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function packs()
    {
        return $this->hasMany(Pack::class);
    }
}
