<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Upad extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'date',
        'salary',
        'salary_paid',
        'upad',
        'pending',
        'remark'
    ];

    protected $casts = [
        'date' => 'date',
        'salary' => 'decimal:2',
        'upad' => 'decimal:2',
        'pending' => 'decimal:2',
        'salary_paid' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($upad) {
            static::recalculatePendingAmounts($upad->employee_id);
        });

        static::deleted(function ($upad) {
            static::recalculatePendingAmounts($upad->employee_id);
        });
    }

    // Calculate pending month-wise (not cumulative)
    public static function recalculatePendingAmounts($employeeId)
    {
        $records = static::where('employee_id', $employeeId)->orderBy('date')->get();

        // Group by month-year only
        $monthlyGroups = $records->groupBy(function ($record) {
            return $record->date->format('Y-m');
        });

        foreach ($monthlyGroups as $monthYear => $monthRecords) {
            // Calculate only for this month (not cumulative)
            $monthlySalary = $monthRecords->first()->salary;
            $monthlyUpads = $monthRecords->sum('upad');
            $monthlyPending = max($monthlySalary - $monthlyUpads, 0);

            // Update all records in this month with same pending
            foreach ($monthRecords as $record) {
                $record->pending = $monthlyPending;
                $record->saveQuietly();
            }
        }
    }

    // Get monthly summary for an employee
    public static function getMonthlySummary($employeeId)
    {
        $records = static::where('employee_id', $employeeId)->get();

        $monthlyData = $records->groupBy(function ($record) {
            return $record->date->format('Y-m');
        })->map(function ($monthRecords, $monthYear) {
            $firstRecord = $monthRecords->first();
            $totalUpads = $monthRecords->sum('upad');

            return [
                'month_year' => $monthYear,
                'month_name' => Carbon::createFromFormat('Y-m', $monthYear)->format('F Y'),
                'salary' => $firstRecord->salary,
                'total_upads' => $totalUpads,
                'pending' => max($firstRecord->salary - $totalUpads, 0),
                'record_count' => $monthRecords->count()
            ];
        });

        return $monthlyData;
    }
}
