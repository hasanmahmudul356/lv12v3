<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'meter_no',
        'receipt_no',
        'bill_month',
        'bill_amount',
        'payment_amount',
        'payment_date',
        'payment_method',
        'status',
        'user_id'
    ];
    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'meter_no' => 'required',
            'receipt_no' => 'required',
            'bill_month' => 'required',
            'bill_amount' => 'required',
            'payment_amount' => 'required',
            'payment_date' => 'required',
            'payment_method' => 'required',
        ]);

        return $validate;
    }
}
