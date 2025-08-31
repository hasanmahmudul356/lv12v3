<?php

namespace App\Models;

use App\Models\Scopes\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BulkBillGeneration extends Model
{
    use ModelScopes;
    use HasFactory;

    protected $table = 'bulk_bill_generations';
    protected $fillable = ['user_id','meter_number','billing_month','start_reading','end_reading','units_consumed','bill_amount','bill_status'];


    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'user_id' => '',
            'meter_number' => 'required',
            'billing_month' => '',
            'start_reading' => '',
            'end_reading' => '',
            'units_consumed' => '',
            'bill_amount' => '',
            'bill_status' => '',
            'status' => ''
        ]);

        return $validate;
    }
}
