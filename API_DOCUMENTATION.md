# API Documentation

## Authentication

All protected endpoints require a JWT token in the Authorization header:

```
Authorization: Bearer {your_token_here}
```

---

## Public Endpoints

### POST /api/login
Authenticate user and receive JWT token.

**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

**Response (401) - Invalid Credentials:**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

## Protected Endpoints

### Authentication

#### POST /api/logout
Invalidate current JWT token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

#### POST /api/refresh
Refresh JWT token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

#### GET /api/me
Get authenticated user information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "email_verified_at": "2024-10-27T10:00:00.000000Z",
    "created_at": "2024-10-27T10:00:00.000000Z",
    "updated_at": "2024-10-27T10:00:00.000000Z"
  }
}
```

---

### Courses

#### GET /api/courses
List all courses with pagination.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Laravel 11 Masterclass",
      "slug": "laravel-11-masterclass",
      "description": "Complete Laravel 11 course",
      "duration_hours": 50,
      "price": "199.99",
      "is_published": true,
      "published_at": "2024-10-27T10:00:00.000000Z",
      "category": {
        "id": 1,
        "name": "Web Development",
        "slug": "web-development"
      },
      "created_at": "2024-10-27T10:00:00.000000Z",
      "updated_at": "2024-10-27T10:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 15,
    "total": 20
  }
}
```

---

#### GET /api/courses/{id}
Get specific course details with lessons.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Laravel 11 Masterclass",
    "slug": "laravel-11-masterclass",
    "description": "Complete Laravel 11 course",
    "duration_hours": 50,
    "price": "199.99",
    "is_published": true,
    "published_at": "2024-10-27T10:00:00.000000Z",
    "category": {
      "id": 1,
      "name": "Web Development"
    },
    "lessons": [
      {
        "id": 1,
        "title": "Introduction to Laravel",
        "slug": "introduction-to-laravel",
        "duration_minutes": 30,
        "order": 1,
        "is_free": true
      }
    ]
  }
}
```

**Response (404) - Not Found:**
```json
{
  "success": false,
  "message": "Course not found"
}
```

---

#### POST /api/courses
Create a new course.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "category_id": 1,
  "title": "Laravel 11 Complete Guide",
  "slug": "laravel-11-complete-guide",
  "description": "Learn Laravel 11 from scratch",
  "duration_hours": 50,
  "price": 199.99,
  "is_published": true
}
```

**Validation Rules:**
- `category_id`: required, must exist in categories table
- `title`: required, string, max 255 characters
- `slug`: optional, string, unique
- `description`: optional, string
- `duration_hours`: optional, integer, min 0
- `price`: optional, numeric, min 0
- `is_published`: optional, boolean

**Response (201):**
```json
{
  "success": true,
  "message": "Course created successfully",
  "data": {
    "id": 21,
    "title": "Laravel 11 Complete Guide",
    "slug": "laravel-11-complete-guide",
    "category": {
      "id": 1,
      "name": "Web Development"
    }
  }
}
```

---

#### PUT /api/courses/{id}
Update existing course.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body (all fields optional):**
```json
{
  "title": "Laravel 11 Masterclass Updated",
  "price": 249.99,
  "is_published": true
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Course updated successfully",
  "data": {
    "id": 1,
    "title": "Laravel 11 Masterclass Updated",
    "price": "249.99"
  }
}
```

---

#### DELETE /api/courses/{id}
Soft delete a course.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Course deleted successfully"
}
```

---

### Lessons

#### GET /api/lessons
List all lessons with pagination.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Introduction to Laravel",
      "slug": "introduction-to-laravel",
      "content": "Welcome to the Laravel course...",
      "video_url": "https://youtube.com/watch?v=example",
      "duration_minutes": 30,
      "order": 1,
      "is_free": true,
      "course": {
        "id": 1,
        "title": "Laravel 11 Masterclass"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

---

#### GET /api/lessons/{id}
Get specific lesson details.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Introduction to Laravel",
    "slug": "introduction-to-laravel",
    "content": "Welcome to the Laravel course...",
    "video_url": "https://youtube.com/watch?v=example",
    "duration_minutes": 30,
    "order": 1,
    "is_free": true,
    "course": {
      "id": 1,
      "title": "Laravel 11 Masterclass"
    }
  }
}
```

---

#### POST /api/lessons
Create a new lesson.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "course_id": 1,
  "title": "Introduction to Laravel",
  "slug": "introduction-to-laravel",
  "content": "Welcome to the Laravel course...",
  "video_url": "https://youtube.com/watch?v=example",
  "duration_minutes": 30,
  "order": 1,
  "is_free": true
}
```

**Validation Rules:**
- `course_id`: required, must exist in courses table
- `title`: required, string, max 255 characters
- `slug`: optional, string
- `content`: optional, string
- `video_url`: optional, valid URL
- `duration_minutes`: optional, integer, min 0
- `order`: optional, integer, min 0
- `is_free`: optional, boolean

**Response (201):**
```json
{
  "success": true,
  "message": "Lesson created successfully",
  "data": {
    "id": 151,
    "title": "Introduction to Laravel",
    "course_id": 1
  }
}
```

---

#### PUT /api/lessons/{id}
Update existing lesson.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body (all fields optional):**
```json
{
  "title": "Advanced Laravel Concepts",
  "duration_minutes": 45
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Lesson updated successfully",
  "data": {
    "id": 1,
    "title": "Advanced Laravel Concepts",
    "duration_minutes": 45
  }
}
```

---

#### DELETE /api/lessons/{id}
Soft delete a lesson.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Lesson deleted successfully"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Token is invalid or expired"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "O email é obrigatório."
    ],
    "password": [
      "A senha deve ter no mínimo 6 caracteres."
    ]
  }
}
```

---

## Rate Limiting

The API currently doesn't implement rate limiting, but it's recommended to add it in production using Laravel's built-in throttle middleware.

---

## Response Format

All successful responses follow this format:

```json
{
  "success": true,
  "data": {},
  "message": "Optional message"
}
```

All error responses follow this format:

```json
{
  "success": false,
  "message": "Error description"
}
```
