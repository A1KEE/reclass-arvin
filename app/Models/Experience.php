<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
    'application_id',
    'school_type',
    'school',
    'position',
    'start_date',
    'end_date',
    'file_path'
];

public function application()
{
    return $this->belongsTo(Application::class, 'application_id');
}
}
