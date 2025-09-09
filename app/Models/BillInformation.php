<?php

namespace App\Models;

use App\Models\Scopes\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BillInformation extends Model
{
    protected $table = 'bill_informations';

    use ModelScopes;
    use HasFactory;

    protected $fillable = ['user_id','meter_no','billing_month','start_reading','end_reading','units_consumed','bill_amount','nesco_unit','unit_rate','nesco_bill'];


    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'user_id' => '',
            'meter_no' => '',
            'billing_month' => '',
            'start_reading' => '',
            'end_reading' => '',
            'units_consumed' => '',
            'bill_amount' => '',
            'nesco_unit' => '',
            'unit_rate' => '',
            'nesco_bill' => '',
            'status' => ''
        ]);

        return $validate;
    }


    public function energyBills()
    {
        return $this->hasMany(EnergyBill::class, 'bill_information_id');
    }


    protected static function booted()
    {
        static::deleting(function ($bill) {
            $bill->energyBills()->delete();
        });
    }
}
