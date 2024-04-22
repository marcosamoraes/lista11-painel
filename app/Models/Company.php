<?php

namespace App\Models;

use App\Http\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'parent_id',
        'name',
        'slug',
        'description',
        'phone',
        'phone2',
        'whatsapp',
        'whatsapp2',
        'opening_hours',
        'opening_24h',
        'cep',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'email',
        'site',
        'facebook',
        'instagram',
        'youtube',
        'google_my_business',
        'video_link',
        'photo_360_link',
        'photo_360_code',
        'payment_methods',
        'image',
        'images',
        'banner',
        'featured',
        'visits',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'image_url',
        'banner_url',
        'images_url',
        'full_address',
        'is_approved',
        'rating'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($company) {
            $slug = Str::slug($company->name);

            if (self::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }

            $company->slug = $slug;
        });

        self::updating(function ($company) {
            $slug = Str::slug($company->name);

            if (self::where('slug', $slug)->where('id', '!=', $company->id)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }

            $company->slug = $slug;
        });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/' . $this->image) : null);
    }

    protected function bannerUrl(): Attribute
    {
        return Attribute::get(fn () => $this->banner ? asset('storage/' . $this->banner) : null);
    }

    protected function imagesUrl(): Attribute
    {
        return Attribute::get(fn () => $this->images ? collect($this->images)->map(fn ($image) => asset('storage/' . $image)) : null);
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::get(fn () => $this->address . ', ' . $this->number . ' - ' . $this->neighborhood . ', ' . $this->city . ' - ' . $this->state . ', ' . $this->cep);
    }

    /**
     * Get the user that register the company.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client that owns the company.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the categories for the company.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'company_categories');
    }

    /**
     * Get the tags for the company.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'company_tags');
    }

    /**
     * Get the orders for the company.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the last order approved.
     */
    public function lastOrderApproved()
    {
        return $this->hasOne(Order::class)->whereIn('status', [OrderStatusEnum::Accomplished, OrderStatusEnum::Opened])->latest();
    }

    /**
     * Query build to return only companies with status = true and with an order not expired with status = approved.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', true)
            ->whereHas('orders', function ($query) {
                $query->whereIn('status', [OrderStatusEnum::Accomplished, OrderStatusEnum::Opened]);
            });
    }

    /**
     * Attribute to return if the company is approved
     */
    public function isApproved(): Attribute
    {
        return Attribute::get(fn () => $this->status);
    }

    /**
     * Get the reviews for the company.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Attribute to return the rating of the company
     */
    public function rating(): Attribute
    {
        return Attribute::get(fn () => $this->reviews()->avg('rating'));
    }

    /**
     * Get the apps for the company.
     */
    public function apps()
    {
        return $this->belongsToMany(App::class, 'company_apps');
    }

    /**
     * Get the company apps for the company.
     */
    public function companyApps()
    {
        return $this->hasMany(CompanyApp::class);
    }

    /**
     * Get the parent company.
     */
    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    /**
     * Get the children companies.
     */
    public function children()
    {
        return $this->hasMany(Company::class, 'parent_id');
    }
}
