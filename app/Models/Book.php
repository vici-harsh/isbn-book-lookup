<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'authors',
        'description',
        'cover_url',
        'pages',
        'publisher',
    ];

    protected $casts = [
        'pages' => 'integer',
    ];
}