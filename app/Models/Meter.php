<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Meter extends Model
{
    use HasFactory;
    protected $fillable = ['meter_number', 'customer_id', 'area_id', 'connection_date', 'due_date', 'meter_type', 'status'];

    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'meter_number' => 'required',
            'customer_id' => 'required',
            'area_id' => 'required',
            'connection_date' => 'required',
            'due_date' => 'required',
            'meter_type' => 'required',
        ]);

        return $validate;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function meterType()
    {
        return $this->hasOne(MeterType::class, 'id', 'meter_type');
    }

}
