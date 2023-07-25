<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function trainees()
    {
        return $this->belongsToMany(Trainee::class, 'trainees_courses');
    }
}
