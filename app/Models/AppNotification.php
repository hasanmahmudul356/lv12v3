<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'title',
        'notification',
        'link',
        'type',
        'type_id',
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->diffForHumans() : null;
    }
}
