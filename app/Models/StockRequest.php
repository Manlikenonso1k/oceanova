<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'ingredient_id',
        'quantity',
        'status',
        'manager_notes',
        'category',
        'requested_by',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'processed_at' => 'datetime',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
