<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Meter extends Model
{
    use HasFactory;
    protected $fillable = ['meter_number', 'customer_id', 'connection_date', 'meter_type', 'status'];

    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'meter_number' => 'required',
            'customer_id' => 'required',
            'connection_date' => 'required',
            'meter_type' => 'required',
        ]);

        return $validate;
    }
}
