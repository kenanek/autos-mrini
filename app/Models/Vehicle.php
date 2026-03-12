<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'car_model_id',
        'title',
        'slug',
        'year',
        'price',
        'old_price',
        'mileage',
        'fuel_type',
        'transmission',
        'body_type',
        'color',
        'interior_color',
        'engine_size',
        'horsepower',
        'doors',
        'seats',
        'vin',
        'description',
        'short_description',
        'condition',
        'status',
        'is_featured',
        'featured_image',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Vehicle $vehicle) {
            if (empty($vehicle->slug)) {
                $baseSlug = Str::slug($vehicle->title);
                $vehicle->slug = $baseSlug . '-' . Str::random(5);
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────────────

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(VehicleFeature::class , 'feature_vehicle')
            ->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function customAttributes(): BelongsToMany
    {
        return $this->belongsToMany(CustomAttribute::class , 'vehicle_custom_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'available')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min)
            $query->where('price', '>=', $min);
        if ($max)
            $query->where('price', '<=', $max);
        return $query;
    }

    public function scopeByYear($query, $min = null, $max = null)
    {
        if ($min)
            $query->where('year', '>=', $min);
        if ($max)
            $query->where('year', '<=', $max);
        return $query;
    }

    // ── Accessors ──────────────────────────────────────────────────────

    public function getFormattedPriceAttribute(): string
    {
        $currency = \App\Models\Setting::getVal('currency', '€');
        $formatted = number_format($this->price, 0, ',', '.');
        return $currency === '€' ? $formatted . ' €' : $formatted . ' ' . $currency;
    }

    public function getFormattedMileageAttribute(): string
    {
        return number_format($this->mileage, 0, ',', ' ') . ' km';
    }

    public function getPrimaryImageAttribute(): ?string
    {
        $primary = $this->images->firstWhere('is_primary', true);
        return $primary ? $primary->image_path : ($this->featured_image ?? null);
    }

    public function getIsNewAttribute(): bool
    {
        return $this->condition === 'new';
    }

    public function incrementViewCount(): void
    {
        $this->increment('views_count');
    }
}
