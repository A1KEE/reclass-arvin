<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations'; // plural, tama sa DB
    protected $fillable = [
        'application_id',
        'degree',
        'school',
        'date_graduated',
        'units',
        'file_path',
    ];
}