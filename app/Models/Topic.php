<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'course_id',
    ];

    // Mutator to set the default value for description
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value ?: '';
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'trainers_topics');
    }
}
