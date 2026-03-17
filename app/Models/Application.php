<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
    'uuid',
    'name',
    'current_position',
    'position_applied',
    'item_number',
    'school_name',
    'sg_annual_salary',
    'levels',

    'qs_position_education',
    'qs_applicant_education',
    'remarks_education',

    'qs_position_training',
    'qs_applicant_training',
    'remarks_training',

    'qs_position_experience',
    'qs_applicant_experience',
    'remarks_experience',

    'qs_position_eligibility',
    'qs_applicant_eligibility',
    'remarks_eligibility',

    'status',
    'folder_path',
    'last_activity_at'
];

    protected $casts = [
        'uuid' => 'string',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}