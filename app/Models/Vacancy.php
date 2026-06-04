<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'department',
        'type',
        'description',
        'requirements',
        'closing_date',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'closing_date' => 'date',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
