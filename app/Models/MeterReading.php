<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'meter_no',
        'reading_date',
        'current_reading',
        'status',
        'user_id',
    ];

    public function validate($input){

        $validate = Validator::make($input, [
            'meter_no' => 'required',
            'reading_date' => 'required',
            'current_reading' => 'required',
            'user_id' => 'required',
        ]);

        return $validate;
    }

    public function meter()
    {
        return $this->belongsTo(Meter::class, 'meter_no', 'id');
    }

}
