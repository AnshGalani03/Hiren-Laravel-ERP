<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubContractor extends Model
{
    protected $fillable = [
        'contractor_name',
        'contractor_type', // 'self' or 'third_party'
        'third_party_name', // name when type is third_party
        'date',
        'project_name',
        'department_name',
        'amount_project',
        'time_limit',
        'work_order_date',
        'emd_fdr_detail',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'work_order_date' => 'date',
            'amount_project' => 'decimal:2',
        ];
    }

    public function bills(): HasMany
    {
        return $this->hasMany(SubContractorBill::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Calculate total bill amount
    public function getTotalBillAmountAttribute(): float
    {
        return $this->bills()->sum('amount');
    }

    // Get remaining amount
    public function getRemainingAmountAttribute(): float
    {
        return $this->amount_project - $this->total_bill_amount;
    }

    // Get display name based on contractor type
    public function getDisplayNameAttribute(): string
    {
        return $this->contractor_type === 'third_party' && $this->third_party_name
            ? $this->third_party_name
            : $this->contractor_name;
    }

    // Check if contractor is self
    public function isSelf(): bool
    {
        return $this->contractor_type === 'self';
    }

    // Check if contractor is third party
    public function isThirdParty(): bool
    {
        return $this->contractor_type === 'third_party';
    }

    // Scope for self contractors
    public function scopeSelf($query)
    {
        return $query->where('contractor_type', 'self');
    }

    // Scope for third party contractors
    public function scopeThirdParty($query)
    {
        return $query->where('contractor_type', 'third_party');
    }
}
