<?php

namespace App\Services;

use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Support\Str;

class LessonService
{
    public function __construct(
        protected LessonRepositoryInterface $lessonRepository
    ) {}

    public function getAllLessons()
    {
        return $this->lessonRepository->paginate();
    }

    public function getLessonById(int $id)
    {
        return $this->lessonRepository->find($id);
    }

    public function getLessonBySlug(string $slug)
    {
        return $this->lessonRepository->findBySlug($slug);
    }

    public function getLessonsByCourse(int $courseId)
    {
        return $this->lessonRepository->getLessonsByCourse($courseId);
    }

    public function createLesson(array $data)
    {
        // Gera slug automaticamente se não fornecido
        if (!isset($data['slug']) && isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Define ordem automaticamente se não fornecida
        if (!isset($data['order'])) {
            $lessons = $this->lessonRepository->getLessonsByCourse($data['course_id']);
            $data['order'] = $lessons->count() + 1;
        }

        return $this->lessonRepository->create($data);
    }

    public function updateLesson(int $id, array $data)
    {
        // Atualiza slug se o título for alterado
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $this->lessonRepository->update($id, $data);
    }

    public function deleteLesson(int $id)
    {
        return $this->lessonRepository->delete($id);
    }

    public function updateLessonOrder(int $id, int $order)
    {
        return $this->lessonRepository->updateOrder($id, $order);
    }

    public function getFreeLessons()
    {
        return $this->lessonRepository->getFreeLessons();
    }
}
