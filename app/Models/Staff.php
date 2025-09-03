<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'user_id'
    ];

    public function validate($input){

        $validate = Validator::make($input, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'user_id' => 'required',
        ]);

        return $validate;
    }
}
