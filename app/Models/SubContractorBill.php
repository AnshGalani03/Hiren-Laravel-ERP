<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubContractorBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_contractor_id',
        'bill_no',
        'amount',
        'date',
        'remark'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function subContractor()
    {
        return $this->belongsTo(SubContractor::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'sub_contractor_id', 'sub_contractor_id')
            ->where('description', 'like', '%' . $this->bill_no . '%');
    }

    // Get the specific transaction for this bill
    public function getTransactionAttribute()
    {
        return Transaction::where('sub_contractor_id', $this->sub_contractor_id)
            ->where('description', 'like', '%' . $this->bill_no . '%')
            ->first();
    }
}
