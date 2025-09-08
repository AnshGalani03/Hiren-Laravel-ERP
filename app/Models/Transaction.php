<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'project_id',
        'dealer_id',
        'incoming_id',
        'outgoing_id',
        'amount',
        'date',
        'description',
        'remark'
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

    public function incoming()
    {
        return $this->belongsTo(Incoming::class);
    }

    public function outgoing()
    {
        return $this->belongsTo(Outgoing::class);
    }
}
