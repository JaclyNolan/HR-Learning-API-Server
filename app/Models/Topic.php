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
        'type',
        'type_name',
        'working_place',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'trainers_topics');
    }
}
