<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Many-to-many relationship with Project
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }
}
