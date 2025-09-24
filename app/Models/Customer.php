<?php
// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address', 
        'gst',
        'pan_card', // Added PAN card field
        'phone_no'
    ];
}
