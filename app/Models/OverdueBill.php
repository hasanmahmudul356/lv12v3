<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverdueBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bill_id',
        'penalty_due',
        'due_amount',
        'total_due',
        'status',
        'due_date',
        'user_id'
    ];


}
