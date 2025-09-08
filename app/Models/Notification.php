<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'title', 'message', 'delivery_method', 'is_read'];

    public function validate($input){

        $validate = Validator::make($input, [
            //
        ]);

        return $validate;
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

}
