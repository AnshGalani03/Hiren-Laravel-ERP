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
        'work_order_date',
        'remark'
    ];

    protected $casts = [
        'date' => 'date',
        'work_order_date' => 'date',
        'amount_project' => 'decimal:2'
    ];

    public function bills()
    {
        return $this->hasMany(SubContractorBill::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Calculate total bill amount
    public function getTotalBillAmountAttribute()
    {
        return $this->bills()->sum('amount');
    }

    // Get remaining amount
    public function getRemainingAmountAttribute()
    {
        return $this->amount_project - $this->total_bill_amount;
    }
}
