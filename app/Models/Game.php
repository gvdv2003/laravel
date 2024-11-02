<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{


    protected $fillable = [
        'name',
        'description',
        'year',
        'image_path',
        'created_by',
        'visible'
    ];

    protected $attributes = [
        'visible' => false,
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}


