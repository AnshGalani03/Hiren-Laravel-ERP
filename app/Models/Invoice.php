<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'dealer_id',
        'bill_no',
        'amount', // This will store GST amount only
        'original_amount', // This stores the full amount user entered
        'gst_rate',
        'date',
        'remark'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
            'original_amount' => 'decimal:2',
            'gst_rate' => 'decimal:2',
        ];
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    // Calculate GST amount from original amount
    public static function calculateGstAmount(float $originalAmount, float $gstRate = 18.0): float
    {
        return round(($originalAmount * $gstRate) / 100, 2);
    }

    // Get GST amount (which is stored in 'amount' field)
    public function getGstAmountAttribute(): float
    {
        return $this->amount;
    }

    // Get original amount before GST calculation
    public function getOriginalAmountAttribute(): float
    {
        return $this->attributes['original_amount'] ?? 0;
    }
}
