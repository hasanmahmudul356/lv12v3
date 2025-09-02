<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class generator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'fuel_type',
        'installation_date',
        'user_id'
    ];

    public function validate($input){

        $validate = Validator::make($input, [
            'name' => 'required',
            'code' => 'required',
            'capacity' => 'required',
            'fuel_type' => 'required',
            'installation_date' => 'required',
            'user_id' => 'required',
        ]);

        return $validate;
    }
}
