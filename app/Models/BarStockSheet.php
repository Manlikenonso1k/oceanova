<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarStockSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'period_start',
        'period_end',
        'opening_stock',
        'added_stock',
        'trans_in',
        'trans_out',
        'sales',
        'total_stock',
        'expected_closing',
        'closing_stock',
        'variance',
        'recorded_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'opening_stock' => 'decimal:3',
        'added_stock' => 'decimal:3',
        'trans_in' => 'decimal:3',
        'trans_out' => 'decimal:3',
        'sales' => 'decimal:3',
        'total_stock' => 'decimal:3',
        'expected_closing' => 'decimal:3',
        'closing_stock' => 'decimal:3',
        'variance' => 'decimal:3',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
