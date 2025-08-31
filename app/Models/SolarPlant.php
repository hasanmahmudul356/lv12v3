<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class SolarPlant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'address',
        'city',
        'postal_code',
        'user_id'
    ];

    public function validate($input){

        $validate = Validator::make($input, [
            'name' => 'required',
            'code' => 'required',
            'capacity' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'user_id' => 'required',
        ]);

        return $validate;
    }
}
