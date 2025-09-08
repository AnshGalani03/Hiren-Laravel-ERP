<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'incoming_id',
        'amount',
        'date',
        'remark'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function incoming()
    {
        return $this->belongsTo(Incoming::class);
    }
}
