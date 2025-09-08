<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubContractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_name',
        'date',
        'project_name',
        'department_name',
        'amount_project',
        'time_limit',
        'emd_fdr_detail',
        'expenses',
        'work_order_date',
        'remark'
    ];

    protected $casts = [
        'date' => 'date',
        'work_order_date' => 'date'
    ];

    public function bills()
    {
        return $this->hasMany(SubContractorBill::class);
    }

    // Calculate total bill amount
    public function getTotalBillAmountAttribute()
    {
        return $this->bills()->sum('amount');
    }
}
