<?php
namespace FontManager\Service;

use FontManager\Model\FontGroupModel;
use FontManager\Model\FontModel;
class ValidationException extends \Exception {
    private array $errors;

    public function __construct(string $message, array $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array {
        return $this->errors;
    }
}

class FontGroupService {
    private FontGroupModel $groupModel;
    private FontModel $fontModel;

    public function __construct(
        FontGroupModel $groupModel,
        FontModel $fontModel
    ) {
        $this->groupModel = $groupModel;
        $this->fontModel = $fontModel;
    }

    /**
     * Create a new font group with validation
     * 
     * @param array $groupData
     * @return array
     * @throws ValidationException
     */
    public function createGroup(array $groupData): array {
        $this->validateGroupData($groupData);
        
        try {
            $groupId = $this->groupModel->create($groupData);
            return [
                'success' => true,
                'groupId' => $groupId,
                'message' => 'Font group created successfully'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create font group: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing font group
     * 
     * @param int $groupId
     * @param array $groupData
     * @return array
     * @throws ValidationException
     */
    public function updateGroup(int $groupId, array $groupData): array {
        $this->validateGroupData($groupData);

        try {
            $success = $this->groupModel->update($groupId, $groupData);
            return [
                'success' => $success,
                'message' => $success ? 'Group updated successfully' : 'Failed to update group'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating group: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a font group
     * 
     * @param int $groupId
     * @return array
     */
    public function deleteGroup(int $groupId): array {
        try {
            $success = $this->groupModel->delete($groupId);
            return [
                'success' => $success,
                'message' => $success ? 'Group deleted successfully' : 'Failed to delete group'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting group: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get a single font group with fonts
     * 
     * @param int $groupId
     * @return array
     */
    public function getGroup(int $groupId): array {
        try {
            $group = $this->groupModel->findById($groupId);
            
            if (!$group) {
                return ['success' => false, 'message' => 'Group not found'];
            }

            return [
                'success' => true,
                'group' => $this->enrichGroupWithFonts($group)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching group: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all font groups with their fonts
     * 
     * @return array
     */
    public function getAllGroups(): array {
        try {
            $groups = $this->groupModel->findAll();
            
            return [
                'success' => true,
                'groups' => array_map([$this, 'enrichGroupWithFonts'], $groups)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching groups: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate group data
     * 
     * @param array $groupData
     * @throws ValidationException
     */
    private function validateGroupData(array $groupData): void {
        $errors = [];

        // Validate minimum fonts
        if (count($groupData['fonts'] ?? []) < 2) {
            $errors['fonts'] = 'At least two fonts must be selected';
        }

        // Validate font existence
        if (!empty($groupData['fonts'])) {
            foreach ($groupData['fonts'] as $fontId) {
                if (!$this->fontModel->findById($fontId)) {
                    $errors['fonts'] = "Invalid font ID: $fontId";
                    break;
                }
            }
        }

        if (!empty($errors)) {
            throw new ValidationException('Invalid group data', $errors);
        }
    }

    /**
     * Enrich group data with font details
     * 
     * @param array $group
     * @return array
     */
    private function enrichGroupWithFonts(array $group): array {
        $group['fonts'] = array_filter(array_map(
            function($fontId) {
                return $this->fontModel->findById($fontId);
            },
            $group['font_ids']
        ));

        return $group;
    }
}