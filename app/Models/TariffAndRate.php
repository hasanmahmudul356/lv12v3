<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TariffAndRate extends Model
{
    use HasFactory;
    protected $fillable = ['unit_rate', 'effective_from', 'meter_type', 'status'];

    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'unit_rate' => 'required',
            'effective_from' => 'required',
            'meter_type' => 'required',
        ]);

        return $validate;
    }

    public function meter_type()
    {
        return $this->hasOne(MeterType::class, 'id', 'meter_type');
    }
}
