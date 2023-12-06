<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'answer_table';
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'question_id' => 'string',
        'answer_type' => 'boolean',
        'text' => 'string',
        'path' => 'string',
        'link_image' => 'string'
    ];


    use HasFactory;
}
