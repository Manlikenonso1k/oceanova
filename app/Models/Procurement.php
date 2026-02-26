<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'quantity_received',
        'unit_price',
        'unit_cost',
        'supplier_name',
        'status',
        'receipt_attachment',
        'received_at',
    ];

    protected $casts = [
        'quantity_received' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'received_at' => 'datetime',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
