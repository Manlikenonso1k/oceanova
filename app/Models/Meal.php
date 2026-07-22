<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_section_id',
        'name',
        'slug',
        'price',
        'description',
        'category',
        'image',
        'tags',
        'sort_order',
        'is_active',
        'is_hidden',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tags' => 'array',
        'is_active' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    public function menuSection(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'menu_item_id');
    }
}
