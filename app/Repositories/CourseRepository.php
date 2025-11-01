<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function __construct(
        protected Course $model
    ) {}

    public function all(): Collection
    {
        return $this->model->with('category')->get();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->with('category')->paginate($perPage);
    }

    public function find(int $id): ?Course
    {
        return $this->model->with(['category', 'lessons'])->find($id);
    }

    public function create(array $data): Course
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $course = $this->model->find($id);
        if (!$course) {
            return false;
        }
        return $course->update($data);
    }

    public function delete(int $id): bool
    {
        $course = $this->model->find($id);
        if (!$course) {
            return false;
        }
        return $course->delete();
    }

    public function findBySlug(string $slug): ?Course
    {
        return $this->model->where('slug', $slug)
            ->with(['category', 'lessons'])
            ->first();
    }

    public function getPublishedCourses()
    {
        return $this->model->where('is_published', true)
            ->with('category')
            ->paginate(15);
    }

    public function getCoursesByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)
            ->where('is_published', true)
            ->with('category')
            ->get();
    }

    public function getCourseWithLessons(int $id): ?Course
    {
        return $this->model->with(['lessons' => function ($query) {
            $query->orderBy('order');
        }])->find($id);
    }

    public function getCourseWithEnrollments(int $id): ?Course
    {
        return $this->model->with(['enrollments.user'])->find($id);
    }
}
