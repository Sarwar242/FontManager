<?php
namespace FontManager\Service;

use FontManager\Utility\FileUploader;
use FontManager\Utility\Validator;
use FontManager\Model\FontModel;

class FontUploadService {
    private FileUploader $uploader;
    private Validator $validator;
    private FontModel $fontModel;

    public function __construct(
        FileUploader $uploader, 
        Validator $validator, 
        FontModel $fontModel
    ) {
        $this->uploader = $uploader;
        $this->validator = $validator;
        $this->fontModel = $fontModel;
    }

    public function uploadFont(array $file, string $name, string $fontFamily): array {
        // Validate file
        // if (!$this->validator->isValidFont($file)) {
        //     return [
        //         'success' => false, 
        //         'message' => 'Invalid font file'
        //     ];
        // }

        // Upload file
        $uploadResult = $this->uploader->upload($file, 'fonts');
        
        if (!$uploadResult['success']) {
            return $uploadResult;
        }

        // Save font metadata to database
        $fontId = $this->fontModel->create([
            'filename' => $uploadResult['filename'],
            'originalName' => $name,
            'font_family' => $fontFamily,
            'details' => $uploadResult['details'],
            'filepath' => $uploadResult['filepath']
        ]);

        return [
            'success' => true,
            'fontId' => $fontId,
            'filename' => $uploadResult['filename']
        ];
    }
}