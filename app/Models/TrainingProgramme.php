<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgramme extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'code',
        'description',
        'objective',
        'icon',
        'is_active',
        'order_number',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function courses()
    {
        return $this->hasMany(TrainingCourse::class, 'programme_id');
    }
}
