<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_Quiz extends Model
{

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'type_table';

    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'description' => 'string'
    ];

    use HasFactory;
}
