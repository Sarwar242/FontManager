<?php
namespace FontManager\Controller;

use FontManager\Core\BaseController;
use FontManager\Service\FontUploadService;
use FontManager\Model\FontModel;
use FontManager\Utility\FileUploader;
use FontManager\Utility\Validator;

class FontController extends BaseController {
    private FontUploadService $uploadService;
    private FontModel $fontModel;

    public function __construct() {
        // Dependency Injection
        $this->fontModel = new FontModel();
        $this->uploadService = new FontUploadService(
            new FileUploader(),
            new Validator(),
            $this->fontModel
        );
    }

    /**
     * Handle font upload
     * 
     * @param array $files Uploaded files array containing 'font' key
     * @param string|null $name Font display name
     * @param string|null $fontFamily CSS font-family name
     * @return void
     * @throws \InvalidArgumentException When required parameters are missing
     */
    public function upload(array $files, ?string $name, ?string $fontFamily): void {
        // Validate file upload
        if (!isset($files['font']) || empty($files['font'])) {
            $this->errorResponse('No font file uploaded', 400);
            return;
        }

        // Validate required string parameters
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Font name is required';
        }
        if (empty($fontFamily)) {
            $errors['font_family'] = 'Font family is required';
        }

        if (!empty($errors)) {
            $this->validationErrorResponse($errors);
            return;
        }

        try {
            $result = $this->uploadService->uploadFont($files['font'], $name, $fontFamily);
            
            if ($result['success']) {
                $this->jsonResponse($result);
            } else {
                $this->errorResponse($result['message'], 400);
            }
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * List all uploaded fonts
     * 
     * @return void
     */
    public function listFonts(): void {
        try {
            $fonts = $this->fontModel->findAll();
            $this->jsonResponse([
                'success' => true,
                'fonts' => $fonts
            ]);
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get specific font details
     * 
     * @param int $fontId Font identifier
     * @return void
     */
    public function getFontDetails(int $fontId): void {
        try {
            $font = $this->fontModel->findById($fontId);

            if ($font) {
                $this->jsonResponse([
                    'success' => true,
                    'font' => $font
                ]);
            } else {
                $this->errorResponse('Font not found', 404);
            }
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Delete a font
     * 
     * @param int $fontId Font identifier
     * @return void
     */
    public function delete(int $fontId): void {
        try {
            $success = $this->fontModel->delete($fontId);
            
            if ($success) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Font deleted successfully'
                ]);
            } else {
                $this->errorResponse('Font not found', 404);
            }
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
}