<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'template_type',
        'sections'
    ];

    protected $casts = [
        'sections' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function projets() {

        return $this->hasMany(Project::class);

    }

}