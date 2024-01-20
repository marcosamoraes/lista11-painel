<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'company_id',
        'pack_id',
        'external_id',
        'payment_method',
        'value',
        'parcels',
        'parcels_data',
        'status',
        'payment_code',
        'image',
        'contract_name',
        'contract_cpf',
        'contract_url',
        'contract_ip',
        'contract_signed_at',
        'approved_at',
        'canceled_at',
        'expire_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'canceled_at' => 'datetime',
        'expire_at' => 'datetime',
        'contract_signed_at' => 'datetime',
    ];

    protected $appends = [
        'image_url',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/' . $this->image) : null);
    }

    protected function parcelsData(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => json_decode($value, true),
            set: fn (string|array $value) => is_array($value) ? json_encode($value) : $value
        );
    }

    public function getExpireAt()
    {
        $days = 0;
        switch ($this->pack->validity) {
            case 'Mensal':
                $days = 30;
                break;
            case 'Semestral':
                $days = 180;
                break;
            case 'Anual':
                $days = 365;
                break;
        }

        return now()->addDays($days);
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that owns the order.
     */
    public function company()
    {
        return $this->belongsTo(Company::class)->withTrashed();
    }

    /**
     * Get the pack that owns the order.
     */
    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }
}
