<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'version',
        'effective_date',
        'approving_authority',
        'file_path',
        'file_size',
        'file_type',
        'visibility',
        'is_published',
        'downloads_count',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_published' => 'boolean',
        'downloads_count' => 'integer',
    ];
}
