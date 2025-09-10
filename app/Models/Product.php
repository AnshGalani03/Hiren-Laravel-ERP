<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    // Automatically set date when creating
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (!$product->date) {
                $product->date = Carbon::now()->toDateString();
            }
        });
    }
}
