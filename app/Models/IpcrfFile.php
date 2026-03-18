<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpcrfFile extends Model
{
    use HasFactory;

    protected $table = 'ipcrf_files'; // table name mo sa DB
    protected $fillable = [
        'application_id',
        'file_name',   // wag 'title', kung sa DB column ay file_name
        'file_path'
    ];
}