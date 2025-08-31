<?php

namespace App\Models;

use App\Models\Scopes\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BulkBillGeneration extends Model
{
    use ModelScopes;
    use HasFactory;

    protected $table = 'bulk_bill_generations';
    protected $fillable = ['billing_month','unit_rate'];


    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'billing_month' => 'required',
            'unit_rate' => '',
            'status' => ''
        ]);

        return $validate;
    }
}
