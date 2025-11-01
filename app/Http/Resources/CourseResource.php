<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'duration_hours' => $this->duration_hours,
            'price' => $this->price,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'student_count' => $this->when(isset($this->enrollments_count), $this->enrollments_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
