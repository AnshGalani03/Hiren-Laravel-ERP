<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'customer_id', // Make sure this is customer_id, not dealer_id
        'bill_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'is_gst',
        'status',
        'notes'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_gst' => 'boolean'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            if (!$bill->bill_number) {
                $bill->bill_number = self::generateBillNumber();
            }
        });
    }

    /**
     * Generate next bill number - Simple method
     */
    public static function generateBillNumber()
    {
        $currentYear = now()->format('y'); // 25 for 2025
        $prefix = 'HSN' . $currentYear;

        // Get the last bill number for current year
        $lastBill = self::where('bill_number', 'like', $prefix . '%')
            ->orderBy('bill_number', 'desc')
            ->first();

        if ($lastBill) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastBill->bill_number, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            // First bill of the year
            $nextNumber = 1;
        }

        // Format: HSN25001, HSN25002, etc.
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
