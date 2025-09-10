<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class OverdueBill extends Model
{
    use HasFactory;
    protected $table = 'overdue_bills';
    protected $fillable = ['meter_no', 'bill_id','billing_month', 'penalty_due', 'due_amount', 'total_due', 'status', 'due_date', 'user_id'];
    public function validate($input){

        $validate = Validator::make($input, [
            'meter_no' => 'required',
        ]);

        return $validate;
    }


}
