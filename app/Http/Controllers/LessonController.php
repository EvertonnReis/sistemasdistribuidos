<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Services\LessonService;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    public function __construct(
        protected LessonService $lessonService
    ) {}

    public function index(): JsonResponse
    {
        $lessons = $this->lessonService->getAllLessons();
        
        return response()->json([
            'success' => true,
            'data' => LessonResource::collection($lessons->items()),
            'meta' => [
                'current_page' => $lessons->currentPage(),
                'last_page' => $lessons->lastPage(),
                'per_page' => $lessons->perPage(),
                'total' => $lessons->total(),
            ]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $lesson = $this->lessonService->getLessonById($id);

        if (!$lesson) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new LessonResource($lesson)
        ]);
    }

    public function store(StoreLessonRequest $request): JsonResponse
    {
        $lesson = $this->lessonService->createLesson($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lesson created successfully',
            'data' => new LessonResource($lesson)
        ], 201);
    }

    public function update(UpdateLessonRequest $request, int $id): JsonResponse
    {
        $updated = $this->lessonService->updateLesson($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        $lesson = $this->lessonService->getLessonById($id);

        return response()->json([
            'success' => true,
            'message' => 'Lesson updated successfully',
            'data' => new LessonResource($lesson)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->lessonService->deleteLesson($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson deleted successfully'
        ]);
    }
}
