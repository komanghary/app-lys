<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTask extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    function user()
    {
        return $this->belongsTo(User::class, foreignKey: "user_id");
    }
}
