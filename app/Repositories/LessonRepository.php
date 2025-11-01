<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository implements LessonRepositoryInterface
{
    public function __construct(
        protected Lesson $model
    ) {}

    public function all(): Collection
    {
        return $this->model->with('course')->get();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->with('course')->paginate($perPage);
    }

    public function find(int $id): ?Lesson
    {
        return $this->model->with('course')->find($id);
    }

    public function create(array $data): Lesson
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $lesson = $this->model->find($id);
        if (!$lesson) {
            return false;
        }
        return $lesson->update($data);
    }

    public function delete(int $id): bool
    {
        $lesson = $this->model->find($id);
        if (!$lesson) {
            return false;
        }
        return $lesson->delete();
    }

    public function findBySlug(string $slug): ?Lesson
    {
        return $this->model->where('slug', $slug)
            ->with('course')
            ->first();
    }

    public function getLessonsByCourse(int $courseId): Collection
    {
        return $this->model->where('course_id', $courseId)
            ->orderBy('order')
            ->get();
    }

    public function getFreeLessons(): Collection
    {
        return $this->model->where('is_free', true)
            ->with('course')
            ->get();
    }

    public function updateOrder(int $id, int $order): bool
    {
        $lesson = $this->model->find($id);
        if (!$lesson) {
            return false;
        }
        return $lesson->update(['order' => $order]);
    }
}
