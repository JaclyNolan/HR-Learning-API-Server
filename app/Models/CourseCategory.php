<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    // Mutator to set the default value for description
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value ?: '';
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
