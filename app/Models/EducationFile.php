<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EducationFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'file_path',
        'original_name',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}

