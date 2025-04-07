<?php
namespace FontManager\Core;

abstract class BaseController {
    /**
     * Send JSON response
     * 
     * @param mixed $data Response data
     * @param int $statusCode HTTP status code
     */
    protected function jsonResponse($data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Handle validation errors
     * 
     * @param array $errors Validation errors
     */
    protected function validationErrorResponse(array $errors): void {
        $this->jsonResponse([
            'success' => false,
            'errors' => $errors
        ], 400);
    }

    /**
     * Handle server errors
     * 
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     */
    protected function errorResponse(string $message, int $statusCode = 500): void {
        $this->jsonResponse([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}