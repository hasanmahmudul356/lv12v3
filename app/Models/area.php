<?php

namespace App\Models;

use function Carbon\this;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class area extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'code',
        'zone',
        'city',
        'officer_id',
        'status',
        'user_id'
    ];

    public function validate($input){

        $validate = Validator::make($input, [
            'name' => 'required',
            'code' => 'required',
            'zone' => 'required',
            'city' => 'required',
            'officer_id' => 'required',
        ]);

        return $validate;
    }

    public function areaStaff(){
        return $this->belongsTo(Staff::class, 'officer_id', 'id');
    }
}
