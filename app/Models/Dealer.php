<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_name',
        'mobile_no',
        'gst',
        'address',
        'account_no',
        'account_name',
        'ifsc',
        'bank_name'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // New: Get transactions linked to this dealer
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Calculate total invoice amount
    public function getTotalInvoiceAmountAttribute()
    {
        return $this->invoices()->sum('amount');
    }

    // Calculate total transactions amount
    public function getTotalTransactionAmountAttribute()
    {
        return $this->transactions()->sum('amount');
    }

    // Get all financial activities
    public function getAllActivitiesAttribute()
    {
        return $this->total_invoice_amount + $this->total_transaction_amount;
    }
}
