<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'mobile_no',
        'alt_contact_no',
        'pan_no',
        'aadhar_no',
        'salary',
        'pf',
        'esic',
        'bank_name',
        'account_no',
        'ifsc'
    ];

    public function upads()
    {
        return $this->hasMany(Upad::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    // Month-wise Upads Calculations

    /**
     * Get upads for a specific month and year
     */
    public function getMonthlyUpads($month, $year)
    {
        return $this->upads()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('upad');
    }

    /**
     * Get salary paid for a specific month and year
     */
    public function getMonthlySalaryPaid($month, $year)
    {
        return $this->upads()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('salary');
    }

    /**
     * Get pending amount for a specific month
     */
    public function getMonthlyPendingAmount($month, $year)
    {
        $record = $this->upads()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->latest('date')
            ->first();

        return $record ? $record->pending : 0;
    }

    /**
     * Calculate net salary for a specific month
     */
    public function calculateMonthlySalary($month, $year)
    {
        $monthlyUpads = $this->getMonthlyUpads($month, $year);
        $monthlySalaryPaid = $this->getMonthlySalaryPaid($month, $year);
        $monthlyPending = $this->getMonthlyPendingAmount($month, $year);

        return [
            'month' => Carbon::create($year, $month)->format('F Y'),
            'base_salary' => $this->salary,
            'upads_given' => $monthlyUpads,
            'salary_paid' => $monthlySalaryPaid,
            'monthly_balance' => $monthlyUpads - $monthlySalaryPaid,
            'cumulative_pending' => $monthlyPending,
            'net_salary' => $this->salary - $monthlyUpads + $monthlySalaryPaid,
        ];
    }

    /**
     * Get total upads given to employee (all time)
     */
    public function getTotalUpadsAttribute()
    {
        return $this->upads()->sum('upad');
    }

    /**
     * Get total salary paid to employee (all time)
     */
    public function getTotalSalaryPaidAttribute()
    {
        return $this->upads()->sum('salary');
    }

    /**
     * Get current pending amount (from latest record)
     */
    public function getCurrentPendingAttribute()
    {
        $latestRecord = $this->upads()->latest('date')->first();
        return $latestRecord ? $latestRecord->pending : 0;
    }

    /**
     * Get last upad record
     */
    public function getLastUpadAttribute()
    {
        return $this->upads()->latest('date')->first();
    }

    /**
     * Get monthly summary for the last 12 months
     */
    public function getMonthlySummary($months = 12)
    {
        $summary = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < $months; $i++) {
            $month = $currentDate->copy()->subMonths($i)->month;
            $year = $currentDate->copy()->subMonths($i)->year;

            $summary[] = $this->calculateMonthlySalary($month, $year);
        }

        return array_reverse($summary);
    }

    // Transaction relationship
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    // Calculate pending salary
    public function getPendingSalaryAttribute()
    {
        return $this->upads()
            ->where('payment_status', 'pending')
            ->sum('total_amount');
    }
}
