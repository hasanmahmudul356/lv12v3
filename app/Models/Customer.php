<?php

namespace App\Models;

use App\Models\Scopes\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Customer extends Model
{
    protected $table = 'customers';

    use ModelScopes;

    use HasFactory;

    protected $fillable = ['user_id','name', 'email','phone_number','image','address','house_holding_no','area','dob','meter_type',];

    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'user_id' => '',
            'name' => 'required',
            'email' => '',
            'phone_number' => '',
            'image' => '',
            'address' => '',
            'house_holding_no' => '',
            'area' => '',
            'dob' => '',
            'meter_type' => '',
            'status' => ''
        ]);

        return $validate;
    }
}
