<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RABill extends Model
{
    protected $fillable = [
        'bill_no',
        'date',
        'customer_id',
        'project_id',
        'ra_bill_amount',
        'dept_taxes_overheads',
        'tds_1_percent',
        'rmd_amount',
        'welfare_cess',
        'testing_charges',
        'total_c',
        'sgst_9_percent',
        'cgst_9_percent',
        'igst_0_percent',
        'total_with_gst',
        'total_deductions',
        'net_amount',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'ra_bill_amount' => 'decimal:2',
            'dept_taxes_overheads' => 'decimal:2',
            'tds_1_percent' => 'decimal:2',
            'rmd_amount' => 'decimal:2',
            'welfare_cess' => 'decimal:2',
            'testing_charges' => 'decimal:2',
            'total_c' => 'decimal:2',
            'sgst_9_percent' => 'decimal:2',
            'cgst_9_percent' => 'decimal:2',
            'igst_0_percent' => 'decimal:2',
            'total_with_gst' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public static function generateBillNo(): string
    {
        $currentYear = date('y');
        $prefix = "HSNRA{$currentYear}";
        
        $lastBill = self::where('bill_no', 'like', $prefix . '%')
                       ->orderBy('bill_no', 'desc')
                       ->first();
        
        if ($lastBill) {
            $lastNumber = (int) substr($lastBill->bill_no, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public static function calculateAmounts($raBillAmount, $deptTaxes, $tds1, $rmd, $welfare, $testing)
    {
        $totalC = round($raBillAmount - $deptTaxes, 2);
        $sgst9 = round($totalC * 0.09, 2);
        $cgst9 = round($totalC * 0.09, 2);
        $igst0 = 0;
        $totalWithGst = round($totalC + $sgst9 + $cgst9 + $igst0, 2);
        $totalDeductions = round($tds1 + $rmd + $welfare + $testing, 2);
        $netAmount = round($totalWithGst - $totalDeductions, 2);
        
        return [
            'total_c' => $totalC,
            'sgst_9_percent' => $sgst9,
            'cgst_9_percent' => $cgst9,
            'igst_0_percent' => $igst0,
            'total_with_gst' => $totalWithGst,
            'total_deductions' => $totalDeductions,
            'net_amount' => $netAmount,
        ];
    }

    public function formatAmount($amount)
    {
        return number_format($amount, 0);
    }

    protected static function booted()
    {
        static::creating(function ($raBill) {
            if (empty($raBill->bill_no)) {
                $raBill->bill_no = self::generateBillNo();
            }
        });

        static::saving(function ($raBill) {
            $calculations = self::calculateAmounts(
                $raBill->ra_bill_amount,
                $raBill->dept_taxes_overheads,
                $raBill->tds_1_percent,
                $raBill->rmd_amount,
                $raBill->welfare_cess,
                $raBill->testing_charges
            );
            
            foreach ($calculations as $key => $value) {
                $raBill->$key = $value;
            }
        });
    }
}
