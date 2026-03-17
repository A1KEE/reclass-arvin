<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpstIndicator extends Model
{
    protected $table = 'ppst_indicators';

    protected $fillable = [
        'position_level',
        'domain',
        'indicator_type',
        'indicator_text',
        'order',
    ];
}