<?php
namespace FontManager;

class FontUploader {
    private $uploadDirectory = './uploads/fonts/';
    private $allowedExtensions = ['ttf'];

    public function uploadFont($file) {
        if (!$this->validateFile($file)) {
            return ['success' => false, 'message' => 'Invalid file'];
        }

        $fileName = uniqid() . '_' . $file['name'];
        $targetPath = $this->uploadDirectory . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $fontDetails = $this->getFontDetails($targetPath);
            return [
                'success' => true, 
                'filename' => $fileName,
                'originalName' => $file['name'],
                'details' => $fontDetails
            ];
        }

        return ['success' => false, 'message' => 'Upload failed'];
    }

    private function validateFile($file) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($ext, $this->allowedExtensions) && 
               $file['type'] === 'font/ttf';
    }

    private function getFontDetails($filePath) {
        // Extract font metadata using font libraries like FreeType
        // This is a placeholder for actual font metadata extraction
        return [
            'family' => 'Unknown',
            'style' => 'Regular'
        ];
    }
}
?>