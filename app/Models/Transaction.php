<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'amount',
        'date',
        'description',
        'project_id',
        'dealer_id',
        'sub_contractor_id',
        'customer_id',
        'employee_id',
        'incoming_id',
        'outgoing_id'
    ];

    protected $casts = [
        'date' => 'date',
        'deleted_at' => 'datetime'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function subContractor()
    {
        return $this->belongsTo(SubContractor::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function incoming()
    {
        return $this->belongsTo(Incoming::class);
    }

    public function outgoing()
    {
        return $this->belongsTo(Outgoing::class);
    }

    // Helper method to get category name
    public function getCategoryAttribute()
    {
        if ($this->type === 'incoming' && $this->incoming) {
            return $this->incoming->name;
        } elseif ($this->type === 'outgoing' && $this->outgoing) {
            return $this->outgoing->name;
        }
        return 'General';
    }
}
