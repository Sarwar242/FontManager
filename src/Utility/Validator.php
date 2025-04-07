<?php
namespace FontManager\Utility;

class Validator {
    /**
     * Allowed font file extensions
     */
    private const ALLOWED_EXTENSIONS = [
        'ttf', 'otf', 'woff', 'woff2', 'eot'
    ];

    /**
     * Maximum allowed font file size (in bytes)
     * Default: 5MB
     */
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 megabytes

    /**
     * Validate uploaded font file
     * 
     * @param array $file Uploaded file details
     * @return array Validation result
     */
    public function validateFontFile(array $file): array {
        // Check if file was uploaded successfully
        if (!$this->isValidUpload($file)) {
            return [
                'success' => false, 
                'message' => 'Invalid file upload'
            ];
        }

        // Validate file extension
        if (!$this->isValidExtension($file['name'])) {
            return [
                'success' => false, 
                'message' => 'Invalid file type. Allowed types: ' . implode(', ', self::ALLOWED_EXTENSIONS)
            ];
        }

        // Validate file size
        if (!$this->isValidFileSize($file['size'])) {
            return [
                'success' => false, 
                'message' => 'File size exceeds maximum limit of ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB'
            ];
        }

        // Additional MIME type check for font files
        if (!$this->isValidMimeType($file['tmp_name'])) {
            return [
                'success' => false, 
                'message' => 'Invalid font file'
            ];
        }

        return [
            'success' => true,
            'message' => 'File validation successful'
        ];
    }

    /**
     * Check if file was uploaded successfully
     * 
     * @param array $file Uploaded file details
     * @return bool
     */
    private function isValidUpload(array $file): bool {
        return isset($file['error']) && 
               $file['error'] === UPLOAD_ERR_OK && 
               is_uploaded_file($file['tmp_name']);
    }

    /**
     * Validate file extension
     * 
     * @param string $filename Name of the uploaded file
     * @return bool
     */
    private function isValidExtension(string $filename): bool {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, self::ALLOWED_EXTENSIONS);
    }

    /**
     * Validate file size
     * 
     * @param int $fileSize Size of the uploaded file
     * @return bool
     */
    private function isValidFileSize(int $fileSize): bool {
        return $fileSize > 0 && $fileSize <= self::MAX_FILE_SIZE;
    }

    /**
     * Validate MIME type of the font file
     * 
     * @param string $tmpName Temporary filename of the uploaded file
     * @return bool
     */
    private function isValidMimeType(string $tmpName): bool {
        $allowedMimeTypes = [
            'font/ttf' => 'ttf',
            'font/otf' => 'otf',
            'application/font-woff' => 'woff',
            'font/woff2' => 'woff2',
            'application/vnd.ms-fontobject' => 'eot'
        ];

        // Use finfo to detect MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        return isset($allowedMimeTypes[$mimeType]);
    }

    /**
     * Optional: Advanced font file validation
     * Checks if the file is a valid font file using library or additional checks
     * 
     * @param string $tmpName Temporary filename of the uploaded file
     * @return bool
     */
    public function isValidFont(string $tmpName): bool {
        // This method can be expanded with more sophisticated font validation
        // For example, using libraries like 'font-lib' or custom font parsing
        
        // Basic implementation: Check file readability
        return is_readable($tmpName);
    }
}