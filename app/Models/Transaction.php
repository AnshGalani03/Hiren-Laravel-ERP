<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'date',
        'description',
        'project_id',
        'dealer_id',
        'sub_contractor_id', // Add this
        'incoming_id',
        'outgoing_id'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function subContractor()
    {
        return $this->belongsTo(SubContractor::class);
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
