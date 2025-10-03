<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'department_name',
        'amount_project',
        'time_limit',
        'emd_fdr_detail',
        'work_order_date',
        'remark',
        'percentage',
        'final_project_amount',
        'active'  // Added active field
    ];

    protected $casts = [
        'date' => 'date',
        'work_order_date' => 'date',
        'active' => 'boolean',  // Added boolean casting
        'final_project_amount' => 'decimal:2',
    ];

    public function expenses()
    {
        return $this->hasMany(ProjectExpense::class);
    }

    public function incomes()
    {
        return $this->hasMany(ProjectIncome::class);
    }

    // Many-to-many relationship with Employee
    public function employees()
    {
        return $this->belongsToMany(Employee::class)->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Calculate total expenses from all sources
    public function getTotalExpensesAttribute()
    {
        $projectExpenses = $this->expenses()->sum('amount');
        $transactionExpenses = $this->transactions()->where('type', 'outgoing')->sum('amount');
        return $projectExpenses + $transactionExpenses;
    }

    // Calculate total incomes from all sources
    public function getTotalIncomesAttribute()
    {
        $projectIncomes = $this->incomes()->sum('amount');
        $transactionIncomes = $this->transactions()->where('type', 'incoming')->sum('amount');
        return $projectIncomes + $transactionIncomes;
    }

    // Calculate net profit/loss
    public function getNetProfitAttribute()
    {
        return $this->total_incomes - $this->total_expenses;
    }

    // Scope for active projects
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Scope for inactive projects
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function raBills(): HasMany
    {
        return $this->hasMany(RABill::class);
    }
}
