<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'account',
        'age',
        'date_of_birth',
        'education',
        'main_programming_language',
        'toeic_score',
        'experience_details',
        'department',
        'location',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'trainees_courses');
    }
}
