<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incoming extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projectIncomes()
    {
        return $this->hasMany(ProjectIncome::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'incoming_id');
    }
}
