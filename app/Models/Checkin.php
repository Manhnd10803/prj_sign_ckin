<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'type',
        'start_time',
        'end_time',
        'signature',
        'username',
    ];
}
