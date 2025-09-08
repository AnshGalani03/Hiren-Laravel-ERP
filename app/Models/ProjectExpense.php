<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'outgoing_id',
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

    public function outgoing()
    {
        return $this->belongsTo(Outgoing::class);
    }
}
