<?php 
class FontGroupManager {
    private $fontGroups = [];

    public function createFontGroup($fonts) {
        if (count($fonts) < 2) {
            return ['success' => false, 'message' => 'At least two fonts required'];
        }

        $groupId = uniqid('fontgroup_');
        $this->fontGroups[$groupId] = $fonts;

        return [
            'success' => true, 
            'groupId' => $groupId, 
            'fonts' => $fonts
        ];
    }

    public function listFontGroups() {
        return $this->fontGroups;
    }

    public function deleteFontGroup($groupId) {
        if (isset($this->fontGroups[$groupId])) {
            unset($this->fontGroups[$groupId]);
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Group not found'];
    }
}
?>