<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'price_ngn',
        'opening_stock',
        'added_stock',
        'trans_in',
        'trans_out',
        'total_stock',
        'sales',
        'closing_stock',
        'remarks',
        'category',
        'stock_date',
        'recorded_by',
    ];

    protected $casts = [
        'price_ngn' => 'decimal:2',
        'opening_stock' => 'decimal:3',
        'added_stock' => 'decimal:3',
        'trans_in' => 'decimal:3',
        'trans_out' => 'decimal:3',
        'total_stock' => 'decimal:3',
        'sales' => 'decimal:3',
        'closing_stock' => 'decimal:3',
        'stock_date' => 'date',
    ];

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public static function calculateTotals(float $opening, float $added, float $in, float $out, float $closing): array
    {
        $total = ($opening + $added + $in) - $out;
        $sales = $total - $closing;

        return [
            'total_stock' => round($total, 3),
            'sales' => round($sales, 3),
        ];
    }
}
