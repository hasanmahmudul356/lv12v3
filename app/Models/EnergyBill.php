<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class EnergyBill extends Model
{
    use HasFactory;
    protected $table = 'energy_bills';
    protected $fillable = ['type','user_id','billing_month','unit','unit_rate','customer_unit','status'];
    public function validate($input){

        $validate = Validator::make($input, [
            'type' => 'required',
            'billing_month' => 'required',
            'unit' => 'required',
            'unit_rate' => 'required',
            'customer_unit' => 'required',
        ]);

        return $validate;
    }
}
