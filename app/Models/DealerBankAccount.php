<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'account_name',
        'account_no',
        'bank_name',
        'ifsc',
        'notes'
    ];

    // Relationship with Dealer
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    // Get masked account number for display
    public function getMaskedAccountNoAttribute()
    {
        $accountNo = $this->account_no;
        if (strlen($accountNo) > 4) {
            return 'XXXX' . substr($accountNo, -4);
        }
        return $accountNo;
    }
}
