<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outgoing extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projectExpenses()
    {
        return $this->hasMany(ProjectExpense::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'outgoing_id');
    }
}
