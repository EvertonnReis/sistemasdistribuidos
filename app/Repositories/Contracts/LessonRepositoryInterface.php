<?php

namespace App\Repositories\Contracts;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

interface LessonRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Lesson;
    
    public function getLessonsByCourse(int $courseId): Collection;
    
    public function getFreeLessons(): Collection;
    
    public function updateOrder(int $id, int $order): bool;
}
