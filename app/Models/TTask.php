<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTask extends Model
{
    use SoftDeletes;
    protected $casts = [
        'created_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'file_done',
        'deadline',
        'revisi',
        'status',
        'complated_at',
        'file',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: "user_id");
    }

    public function scopeSearch($query, $term)
    {
        return $query->where("keterangan", "like", "%$term%")->orWhereHas('user', function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%");
        });
    }


}
