<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'quiz_table';
    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'description' => 'string',
        'image_path' => 'string',
        'link_image' => 'string',
        'quantity' => 'string'
    ];


    use HasFactory;
}
