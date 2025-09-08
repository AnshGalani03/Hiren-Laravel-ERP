<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'bill_no',
        'amount',
        'date',
        'remark'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
}
