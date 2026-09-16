<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = ['council_id', 'name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }
}
