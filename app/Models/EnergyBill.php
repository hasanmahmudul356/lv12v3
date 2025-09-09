<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class EnergyBill extends Model
{
    use HasFactory;
    protected $table = 'energy_bills';
    protected $fillable = ['bill_information_id', 'meter_no', 'type', 'customer_unit', 'unit_rate', 'bill_amount'];


    public function validate($input){

        $validate = Validator::make($input, [
            'bill_information_id' => 'required',
            'meter_no' => 'required',
            'type' => 'required',
            'customer_unit' => 'required',
            'unit_rate' => 'required',
            'bill_amount' => 'required',
        ]);

        return $validate;
    }

    public function billInformation()
    {
        return $this->belongsTo(BillInformation::class,'bill_information_id');
    }
}
