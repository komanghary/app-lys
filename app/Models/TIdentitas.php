<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TIdentitas extends Model
{
    use SoftDeletes;

    protected $table = "t_identitas";

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
