<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationPpstRating extends Model
{
    protected $fillable = [
        'application_id',
        'ppst_indicator_id',
        'rating'
    ];
}
