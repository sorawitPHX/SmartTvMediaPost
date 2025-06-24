<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SmartTv extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_public'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
