<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'index', 'faculty', 'course', 'speciality', 'title', 'subtitle'];
    protected $hidden = ['created_at', 'updated_at'];
}
