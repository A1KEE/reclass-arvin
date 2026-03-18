<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'title',
        'type',
        'start_date',
        'end_date',
        'hours',
        'file_path'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}