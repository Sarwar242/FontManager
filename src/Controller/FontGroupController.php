<?php
namespace FontManager\Controller;

use FontManager\Core\BaseController;
use FontManager\Model\FontGroupModel;
use FontManager\Model\FontModel;

class FontGroupController extends BaseController {
    private FontGroupModel $fontGroupModel;
    private FontModel $fontModel;

    public function __construct() {
        $this->fontGroupModel = new FontGroupModel();
        $this->fontModel = new FontModel();
    }

    /**
     * Create a new font group
     * 
     * @param array $data Font group data
     * @return void
     */
    public function create(array $data): void {
        // Validate input
        // $errors = $this->validateFontGroupData($data);
        // if (!empty($errors)) {
        //     $this->validationErrorResponse($errors);
        // }

        try {
            // Validate fonts exist
            $this->validateFontExistence($data['fontIds']);

            $result = $this->fontGroupModel->create([
                'name' => $data['name'] ?? 'Unnamed Group',
                'fonts' => $data['fontIds']
            ]);

            $this->jsonResponse([
                'success' => true,
                'groupId' => $result
            ]);
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * List all font groups
     * 
     * @return void
     */
    public function listGroups(): void {
        try {
            $groups = $this->fontGroupModel->findAll();
        
            // Enrich groups with font details
            $enrichedGroups = array_map(function($group) {
                $group['fonts'] = array_map(function($fontId) {
                    // Convert font ID to integer
                    return $this->fontModel->findById((int)$fontId);
                }, $group['font_ids']);
                return $group;
            }, $groups);

            $this->jsonResponse([
                'success' => true,
                'groups' => $enrichedGroups
            ]);
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update an existing font group
     * 
     * @param int $groupId Font group identifier
     * @param array $data Updated font group data
     * @return void
     */
    public function update(int $groupId, array $data): void {
        // Validate input
        $errors = $this->validateFontGroupData($data);
        if (!empty($errors)) {
            $this->validationErrorResponse($errors);
        }

        try {
            // Check if group exists
            $existingGroup = $this->fontGroupModel->findById($groupId);
            if (!$existingGroup) {
                $this->errorResponse('Font group not found', 404);
            }

            // Validate fonts exist
            $this->validateFontExistence($data['fontIds']);

            $updated = $this->fontGroupModel->update($groupId, [
                'name' => $data['name'] ?? $existingGroup['name'],
                'fonts' => $data['fontIds']
            ]);

            $this->jsonResponse([
                'success' => true,
                'updated' => $updated
            ]);
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Delete a font group
     * 
     * @param int $groupId Font group identifier
     * @return void
     */
    public function delete(int $groupId): void {
        try {
            $deleted = $this->fontGroupModel->delete($groupId);

            if ($deleted) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Font group deleted successfully'
                ]);
            } else {
                $this->errorResponse('Failed to delete font group', 500);
            }
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Validate font group input data
     * 
     * @param array $data Input data
     * @return array Validation errors
     */
    private function validateFontGroupData(array $data): array {
        $errors = [];

        if (!isset($data['fontIds']) || !is_array($data['fontIds']) || count($data['fontIds']) < 2) {
            $errors['fonts'] = 'At least two fonts are required';
        }

        return $errors;
    }

    /**
     * Validate that all provided font IDs exist
     * 
     * @param array $fontIds Font identifiers
     * @throws \Exception If any font does not exist
     */
    private function validateFontExistence(array $fontIds): void {
        foreach ($fontIds as $fontId) {
            $font = $this->fontModel->findById((int)$fontId);
            if (!$font) {
                throw new \Exception("Font with ID {$fontId} not found");
            }
        }
    }
}