<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $message = 'Operation Successful', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    /**
     * Return a paginated success response with data.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginatedResponse($data, $message = 'Operation Successful', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data->items(),
            'meta' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
            ],
        ], $code);
    }
    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    /**
     * Return a validation error response.
     *
     * @param array $errors
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationErrorResponse($errors, $message = 'Validation Failed', $code = 422)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
