<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubContractorBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_contractor_id',
        'bill_no',          // Added bill_no field
        'amount',
        'date',
        'remark'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function subContractor()
    {
        return $this->belongsTo(SubContractor::class);
    }
}
