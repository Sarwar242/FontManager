<?php
namespace FontManager\Utility;

class FileUploader {
    private string $uploadDir;
    private array $allowedExtensions;

    public function __construct(
        string $uploadDir = './uploads/fonts', 
        array $allowedExtensions = ['ttf']
    ) {
        $this->uploadDir = rtrim($uploadDir, '/');
        $this->allowedExtensions = $allowedExtensions;

        // Ensure upload directory exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload(array $file, string $subDir = ''): array {
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        
        // Full path
        $fullPath = $this->uploadDir . 
            ($subDir ? '/' . trim($subDir, '/') : '') . 
            '/' . $filename;

        // Ensure subdirectory exists
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $fullPath,
                'details' => $this->extractFontDetails($fullPath)
            ];
        }

        return [
            'success' => false, 
            'message' => 'File upload failed'
        ];
    }

    private function extractFontDetails(string $filepath): array {
        // Placeholder for font metadata extraction
        // In a real-world scenario, use a library like FreeType
        return [
            'family' => 'Unknown',
            'style' => 'Regular'
        ];
    }
}