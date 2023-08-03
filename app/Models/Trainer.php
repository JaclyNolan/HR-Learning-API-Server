<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'education',
        'working_place',
        'phone_number',
        'email',
    ];

    public function users()
    {
        return $this->hasOne(User::class)->latestOfMany();
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'trainers_topics');
    }
}
