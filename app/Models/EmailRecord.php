<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRecord extends Model
{
    protected $fillable = [
        'to',
        'subject',
        'message',
        'status',
        'sent_at',
    ];
}
