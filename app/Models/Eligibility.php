<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eligibility extends Model
{
    use HasFactory;

    protected $table = 'eligibilities'; // pangalan ng table mo
    protected $fillable = [
        'application_id',
        'eligibility_name',
        'expiry_date',
        'file_path'
    ];
      public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
