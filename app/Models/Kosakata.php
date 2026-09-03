<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kosakata extends Model
{
    use HasFactory;

    protected $fillable = [
        'english',
        'indonesia',
        'contoh',
    ];
}
