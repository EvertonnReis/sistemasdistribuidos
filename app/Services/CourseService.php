<?php

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Str;

class CourseService
{
    public function __construct(
        protected CourseRepositoryInterface $courseRepository
    ) {}

    public function getAllCourses()
    {
        return $this->courseRepository->paginate();
    }

    public function getPublishedCourses()
    {
        return $this->courseRepository->getPublishedCourses();
    }

    public function getCourseById(int $id)
    {
        return $this->courseRepository->getCourseWithLessons($id);
    }

    public function getCourseBySlug(string $slug)
    {
        return $this->courseRepository->findBySlug($slug);
    }

    public function createCourse(array $data)
    {
        // Gera slug automaticamente se não fornecido
        if (!isset($data['slug']) && isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Define data de publicação se o curso for publicado
        if (isset($data['is_published']) && $data['is_published']) {
            $data['published_at'] = now();
        }

        return $this->courseRepository->create($data);
    }

    public function updateCourse(int $id, array $data)
    {
        // Atualiza slug se o título for alterado
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Define data de publicação ao publicar pela primeira vez
        if (isset($data['is_published']) && $data['is_published']) {
            $course = $this->courseRepository->find($id);
            if ($course && !$course->published_at) {
                $data['published_at'] = now();
            }
        }

        return $this->courseRepository->update($id, $data);
    }

    public function deleteCourse(int $id)
    {
        return $this->courseRepository->delete($id);
    }

    public function getCoursesByCategory(int $categoryId)
    {
        return $this->courseRepository->getCoursesByCategory($categoryId);
    }

    public function getCourseWithEnrollments(int $id)
    {
        return $this->courseRepository->getCourseWithEnrollments($id);
    }
}
