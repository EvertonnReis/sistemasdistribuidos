<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Course;
    
    public function getPublishedCourses();
    
    public function getCoursesByCategory(int $categoryId): Collection;
    
    public function getCourseWithLessons(int $id): ?Course;
    
    public function getCourseWithEnrollments(int $id): ?Course;
}
