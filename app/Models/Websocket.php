<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Websocket extends Model
{
    use HasFactory;

    protected $table = 'websockets_statistics_entries';
}
