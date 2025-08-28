<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class MeterType extends Model
{
    use HasFactory;

    protected $table = 'meter_types';

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function validate($input){

        $validate = Validator::make($input, [
            'name' => 'required',
            'user_id' => 'required',
        ]);

        return $validate;
    }

}
