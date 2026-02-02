# Smart Pathshala API Documentation

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:
```
Authorization: Bearer your-token-here
```

## Response Format
All API responses follow this format:
```json
{
    "success": true,
    "message": "Success message",
    "data": {
        // Response data
    }
}
```

For paginated responses:
```json
{
    "success": true,
    "message": "Success message",
    "data": [...],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75,
        "from": 1,
        "to": 15,
        "has_more_pages": true
    }
}
```

## Authentication Endpoints

### Login
```http
POST /api/v1/auth/login
```

**Request Body:**
```json
{
    "email": "admin@school.com",
    "password": "password",
    "device_name": "Mobile App"
}
```

**Response:**
```json
{
    "success": true,
    "message": "User logged in successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@school.com",
            "role": "admin"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Tenant Login
```http
POST /api/v1/auth/tenant-login
```

**Request Body:**
```json
{
    "email": "admin@school.com",
    "password": "password",
    "device_name": "Mobile App"
}
```

### Register
```http
POST /api/v1/auth/register
```

**Request Body:**
```json
{
    "name": "New User",
    "email": "user@school.com",
    "password": "password",
    "password_confirmation": "password",
    "phone": "01234567890",
    "role": "teacher",
    "device_name": "Mobile App"
}
```

### Logout
```http
POST /api/v1/auth/logout
```
*Requires authentication*

### Get User
```http
GET /api/v1/auth/user
```
*Requires authentication*

### Change Password
```http
POST /api/v1/auth/change-password
```
*Requires authentication*

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "new_password": "newpassword",
    "new_password_confirmation": "newpassword"
}
```

## Dashboard Endpoints

### Get Dashboard Data
```http
GET /api/v1/dashboard
```
*Requires authentication*

### Get Dashboard Statistics
```http
GET /api/v1/dashboard/stats
```
*Requires authentication*

## Student Management

### Get All Students
```http
GET /api/v1/students
```
*Requires authentication*

**Query Parameters:**
- `class` - Filter by class
- `section` - Filter by section
- `search` - Search in name, student_id, phone, etc.
- `status` - Filter by status (active, inactive, graduated, transferred)
- `sort_by` - Sort field (default: created_at)
- `sort_order` - Sort order (asc, desc)
- `per_page` - Items per page (default: 15)

### Create Student
```http
POST /api/v1/students
```
*Requires authentication*

**Request Body:**
```json
{
    "name": "Student Name",
    "name_bangla": "ছাত্রের নাম",
    "class": "৫ম",
    "section": "ক",
    "roll_number": 1,
    "phone": "01234567890",
    "email": "student@school.com",
    "father_name": "Father Name",
    "mother_name": "Mother Name",
    "guardian_phone": "01234567890",
    "address": "Student Address",
    "date_of_birth": "2010-01-01",
    "gender": "male",
    "blood_group": "A+",
    "religion": "Islam",
    "nationality": "Bangladeshi",
    "admission_date": "2024-01-01"
}
```

### Get Student
```http
GET /api/v1/students/{id}
```
*Requires authentication*

### Update Student
```http
PUT /api/v1/students/{id}
```
*Requires authentication*

### Delete Student
```http
DELETE /api/v1/students/{id}
```
*Requires authentication*

### Get Students by Class
```http
GET /api/v1/students/class/{class}
```
*Requires authentication*

### Upload Student Photo
```http
POST /api/v1/students/{id}/photo
```
*Requires authentication*

**Request Body:** (multipart/form-data)
- `photo` - Image file (jpeg, png, jpg, max 5MB)

## Teacher Management

### Get All Teachers
```http
GET /api/v1/teachers
```
*Requires authentication*

**Query Parameters:**
- `subject` - Filter by subject
- `designation` - Filter by designation
- `search` - Search in name, teacher_id, phone, etc.
- `status` - Filter by status
- `sort_by` - Sort field
- `sort_order` - Sort order
- `per_page` - Items per page

### Create Teacher
```http
POST /api/v1/teachers
```
*Requires authentication*

### Get Teacher
```http
GET /api/v1/teachers/{id}
```
*Requires authentication*

### Update Teacher
```http
PUT /api/v1/teachers/{id}
```
*Requires authentication*

### Delete Teacher
```http
DELETE /api/v1/teachers/{id}
```
*Requires authentication*

## Homework Management

### Get All Homework (Authenticated)
```http
GET /api/v1/homework
```
*Requires authentication*

**Query Parameters:**
- `class` - Filter by class
- `section` - Filter by section
- `subject` - Filter by subject
- `status` - Filter by status
- `search` - Search in title, description, subject
- `date_from` - Filter from date
- `date_to` - Filter to date
- `sort_by` - Sort field
- `sort_order` - Sort order
- `per_page` - Items per page

### Get Public Homework
```http
GET /api/v1/homework
```
*No authentication required*

**Query Parameters:**
- `class` - Filter by class
- `subject` - Filter by subject
- `search` - Search term

**Response:**
```json
{
    "success": true,
    "message": "Public homework retrieved successfully",
    "data": {
        "upcoming": [...],
        "recent": [...]
    }
}
```

### Create Homework
```http
POST /api/v1/homework
```
*Requires authentication*

**Request Body:**
```json
{
    "title": "Math Exercise",
    "description": "Complete exercises from page 50-52",
    "subject": "গণিত",
    "class": "৫ম",
    "section": "ক",
    "assigned_date": "2024-01-18",
    "due_date": "2024-01-21",
    "instructions": "Show all work steps",
    "status": "active"
}
```

### Get Homework
```http
GET /api/v1/homework/{id}
```
*Requires authentication*

### Get Homework Details (Public)
```http
GET /api/v1/homework/{id}
```
*No authentication required*

### Update Homework
```http
PUT /api/v1/homework/{id}
```
*Requires authentication*

### Delete Homework
```http
DELETE /api/v1/homework/{id}
```
*Requires authentication*

### Get Homework by Class
```http
GET /api/v1/homework/class/{class}
```
*Requires authentication*

### Upload Homework Attachment
```http
POST /api/v1/homework/{id}/attachment
```
*Requires authentication*

## Attendance Management

### Get Attendance Records
```http
GET /api/v1/attendance
```
*Requires authentication*

**Query Parameters:**
- `class` - Filter by class
- `section` - Filter by section
- `date` - Filter by specific date
- `date_from` - Filter from date
- `date_to` - Filter to date
- `status` - Filter by status (present, absent, late, excused)

### Record Attendance
```http
POST /api/v1/attendance
```
*Requires authentication*

**Request Body:**
```json
{
    "student_id": 1,
    "date": "2024-01-18",
    "status": "present",
    "remarks": "On time"
}
```

### Get Class Attendance by Date
```http
GET /api/v1/attendance/class/{class}/date/{date}
```
*Requires authentication*

### Bulk Record Attendance
```http
POST /api/v1/attendance/bulk
```
*Requires authentication*

**Request Body:**
```json
{
    "date": "2024-01-18",
    "class": "৫ম",
    "section": "ক",
    "attendance": [
        {
            "student_id": 1,
            "status": "present",
            "remarks": ""
        },
        {
            "student_id": 2,
            "status": "absent",
            "remarks": "Sick"
        }
    ]
}
```

### Get Student Attendance
```http
GET /api/v1/attendance/student/{studentId}
```
*Requires authentication*

**Query Parameters:**
- `date_from` - Filter from date
- `date_to` - Filter to date

### Get Attendance Reports
```http
GET /api/v1/attendance/reports
```
*Requires authentication*

**Query Parameters:**
- `report_type` - daily, monthly, class_wise, student_wise
- `date` - Required for daily reports
- `month` - Required for monthly reports (format: Y-m)
- `class` - Required for class_wise reports

## Notice Management

### Get All Notices (Authenticated)
```http
GET /api/v1/notices
```
*Requires authentication*

### Get Public Notices
```http
GET /api/v1/notices
```
*No authentication required*

### Create Notice
```http
POST /api/v1/notices
```
*Requires authentication*

**Request Body:**
```json
{
    "title": "Important Notice",
    "content": "Notice content here...",
    "priority": "high",
    "status": "active",
    "publish_date": "2024-01-18",
    "expire_date": "2024-01-25"
}
```

### Get Notice
```http
GET /api/v1/notices/{id}
```
*Requires authentication*

### Get Notice Details (Public)
```http
GET /api/v1/notices/{id}
```
*No authentication required*

### Update Notice
```http
PUT /api/v1/notices/{id}
```
*Requires authentication*

### Delete Notice
```http
DELETE /api/v1/notices/{id}
```
*Requires authentication*

## School Information

### Get Public School Info
```http
GET /api/v1/school/info
```
*No authentication required*

### Get School Settings
```http
GET /api/v1/school/settings
```
*Requires authentication*

### Update School Settings
```http
PUT /api/v1/school/settings
```
*Requires authentication*

**Request Body:**
```json
{
    "type": "school",
    "school_name": "School Name",
    "school_name_bangla": "স্কুলের নাম",
    "address": "School Address",
    "phone": "01234567890",
    "email": "school@example.com"
}
```

### Upload School Logo
```http
POST /api/v1/school/logo
```
*Requires authentication*

### Get Academic Sessions
```http
GET /api/v1/school/academic-sessions
```
*Requires authentication*

### Create Academic Session
```http
POST /api/v1/school/academic-sessions
```
*Requires authentication*

**Request Body:**
```json
{
    "name": "2024-2025",
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "description": "Academic year 2024-2025",
    "is_current": true
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "Resource not found"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Internal Server Error"
}
```

## Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting
API requests are rate limited to prevent abuse. Default limits:
- Authenticated requests: 60 per minute
- Unauthenticated requests: 30 per minute

## File Uploads
- Maximum file size: 5MB
- Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG
- Images are automatically compressed during upload

## Pagination
Most list endpoints support pagination with these parameters:
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15, max: 100)

## Filtering and Sorting
Most list endpoints support:
- `search` - Search term
- `sort_by` - Field to sort by
- `sort_order` - asc or desc
- Various filter parameters specific to each endpoint

## Mobile App Integration
This API is designed for mobile app integration with:
- Token-based authentication
- Optimized response formats
- Image compression
- Offline-friendly data structures
- Push notification support (coming soon)

## Postman Collection
A Postman collection with all endpoints and examples is available for testing.

## Support
For API support, contact: support@smartpathshala.com