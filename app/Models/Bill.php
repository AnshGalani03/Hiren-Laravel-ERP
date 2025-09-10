<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'dealer_id',
        'bill_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'notes',
        'status',
        'is_gst'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_gst' => 'boolean'
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            $bill->bill_number = static::generateBillNumber();
        });
    }

    public static function generateBillNumber()
    {
        $lastBill = static::orderBy('id', 'desc')->first();
        $number = $lastBill ? intval(substr($lastBill->bill_number, 4)) + 1 : 1;
        return 'BILL' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
