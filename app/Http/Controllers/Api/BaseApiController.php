<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Success response method
     */
    public function sendResponse($result, $message = 'Success', $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }

    /**
     * Error response method
     */
    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     */
    public function sendValidationError($errors, $message = 'Validation Error'): JsonResponse
    {
        return $this->sendError($message, $errors, 422);
    }

    /**
     * Unauthorized response
     */
    public function sendUnauthorized($message = 'Unauthorized'): JsonResponse
    {
        return $this->sendError($message, [], 401);
    }

    /**
     * Forbidden response
     */
    public function sendForbidden($message = 'Forbidden'): JsonResponse
    {
        return $this->sendError($message, [], 403);
    }

    /**
     * Not found response
     */
    public function sendNotFound($message = 'Not Found'): JsonResponse
    {
        return $this->sendError($message, [], 404);
    }

    /**
     * Server error response
     */
    public function sendServerError($message = 'Internal Server Error'): JsonResponse
    {
        return $this->sendError($message, [], 500);
    }

    /**
     * Paginated response
     */
    public function sendPaginatedResponse($data, $message = 'Success'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'has_more_pages' => $data->hasMorePages(),
            ],
        ];

        return response()->json($response, 200);
    }
}