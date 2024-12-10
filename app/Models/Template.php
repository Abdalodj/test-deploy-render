<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'html_content',
        'json_structure',
        'thumbnail_url', // Assurez-vous que ce champ est bien dans $fillable
        'deployment_link',
    ];

    protected $casts = [
        'json_structure' => 'array',
    ];
}
