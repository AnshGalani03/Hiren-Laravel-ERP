<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_name',
        'department',
        'amount_emd_fdr',
        'amount_dd',
        'above_below',
        'remark',
        'return_detail',
        'date',
        'result'
    ];

    protected $casts = [
        'date' => 'date'
    ];
}
