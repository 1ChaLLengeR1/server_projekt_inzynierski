<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'game_result';
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'name' => 'string',
        'result' => 'integer',
    ];
    use HasFactory;
}
