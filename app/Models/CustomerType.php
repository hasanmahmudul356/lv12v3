<?php

namespace App\Models;

use App\Models\Scopes\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CustomerType extends Model
{
    use ModelScopes;
    use HasFactory;

    protected $table = 'customer_types';
    protected $fillable = ['name'];

    public function validate($input = [])
    {
        $validate = Validator::make($input, [
            'name' => 'required',
            'status' => ''

        ]);

        return $validate;
    }
}
