<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'target_amount',
        'start_date',
        'target_date',
        'status',
        'current_progress',
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date',
    ];
}
