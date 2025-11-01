<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    public function index(): JsonResponse
    {
        $courses = $this->courseService->getAllCourses();
        
        return response()->json([
            'success' => true,
            'data' => CourseResource::collection($courses->items()),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
            ]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $course = $this->courseService->getCourseById($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CourseResource($course)
        ]);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->createCourse($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => new CourseResource($course)
        ], 201);
    }

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        $updated = $this->courseService->updateCourse($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $course = $this->courseService->getCourseById($id);

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => new CourseResource($course)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->courseService->deleteCourse($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }
}
