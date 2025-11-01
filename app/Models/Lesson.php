<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'content',
        'video_url',
        'duration_minutes',
        'order',
        'is_free',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'order' => 'integer',
            'is_free' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
