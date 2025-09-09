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
        'date',
        'salary',
        'salary_paid',
        'upad',
        'remark'
    ];

    protected $casts = [
        'date' => 'date',
        'salary' => 'decimal:2',
        'upad' => 'decimal:2',
        'salary_paid' => 'boolean'
        // Remove 'pending' from casts since column doesn't exist
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Calculate pending dynamically using accessor
    public function getPendingAttribute()
    {
        $monthYear = $this->date->format('Y-m');
        $monthlyUpads = static::where('employee_id', $this->employee_id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$monthYear])
            ->sum('upad');

        $pending = $this->salary - $monthlyUpads;
        return max($pending, 0); // Never negative
    }

    // Remove the boot method that tries to update non-existent 'pending' column
    protected static function boot()
    {
        parent::boot();

        // No longer need to recalculate since we're using accessor
        // static::saved(function ($upad) {
        //     static::recalculatePendingAmounts($upad->employee_id);
        // });

        // static::deleted(function ($upad) {
        //     static::recalculatePendingAmounts($upad->employee_id);
        // });
    }

    // Keep this method for compatibility with existing views
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
