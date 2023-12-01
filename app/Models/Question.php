<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'question_table';
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'quiz_id' => 'string',
        'type_id' => 'string',
        'text' => 'string',
        'path' => 'string',
        'link_image' => 'string'
    ];


    use HasFactory;
}
