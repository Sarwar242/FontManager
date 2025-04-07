<?php
namespace FontManager\Model;

use FontManager\Core\Database;
use PDO;

class FontGroupModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Create a new font group
     * 
     * @param array $groupData Group information
     * @return int Inserted group ID
     */
    public function create(array $groupData): int {
        try {
            // Begin transaction
            $this->db->beginTransaction();

            // Insert group
            $query = "INSERT INTO font_groups (name, created_at) VALUES (:name, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':name' => $groupData['name'] ?? 'Unnamed Group'
            ]);
            $groupId = $this->db->lastInsertId();

            // Insert font group relations
            $fontStmt = $this->db->prepare(
                "INSERT INTO font_group_fonts (group_id, font_id) VALUES (:group_id, :font_id)"
            );

            foreach ($groupData['fonts'] as $fontId) {
                $fontStmt->execute([
                    ':group_id' => $groupId,
                    ':font_id' => $fontId
                ]);
            }

            // Commit transaction
            $this->db->commit();

            return $groupId;
        } catch (\Exception $e) {
            // Rollback in case of error
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Find all font groups
     * 
     * @return array List of font groups
     */
    public function findAll(): array {
        $query = "
            SELECT fg.id, fg.name, fg.created_at,
                   GROUP_CONCAT(fgf.font_id) as font_ids
            FROM font_groups fg
            LEFT JOIN font_group_fonts fgf ON fg.id = fgf.group_id
            GROUP BY fg.id, fg.name, fg.created_at
            ORDER BY fg.created_at DESC
        ";
        $stmt = $this->db->query($query);
        
        $groups = $stmt->fetchAll();
    
        return array_map(function($group) {
            // Handle NULL case from GROUP_CONCAT when no fonts exist
            $fontIdsString = $group['font_ids'] ?? '';
            
            // Convert comma-separated string to array of integers
            $group['font_ids'] = $fontIdsString !== ''
                ? array_map('intval', explode(',', $fontIdsString))
                : [];
    
            // Convert group ID to integer
            $group['id'] = (int)$group['id'];
            
            return $group;
        }, $groups);
    }

    /**
     * Find a font group by ID
     * 
     * @param int $groupId Group identifier
     * @return array|null Font group details
     */public function findById(int $groupId): ?array {
            $query = "
            SELECT fg.id, fg.name, fg.created_at,
                GROUP_CONCAT(fgf.font_id) as font_ids
            FROM font_groups fg
            LEFT JOIN font_group_fonts fgf ON fg.id = fgf.group_id
            WHERE fg.id = :id
            GROUP BY fg.id, fg.name, fg.created_at
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $groupId]);

            $group = $stmt->fetch();

            if (!$group) {
                return null;
            }

            // Convert font_ids to array safely
            $group['font_ids'] = $group['font_ids'] ? explode(',', $group['font_ids']) : [];

            return $group;
        }


    /**
     * Update an existing font group
     * 
     * @param int $groupId Group identifier
     * @param array $groupData Updated group information
     * @return bool Update success status
     */
    public function update(int $groupId, array $groupData): bool {
        try {
            // Begin transaction
            $this->db->beginTransaction();

            // Update group name
            $nameQuery = "UPDATE font_groups SET name = :name WHERE id = :id";
            $nameStmt = $this->db->prepare($nameQuery);
            $nameStmt->execute([
                ':name' => $groupData['name'],
                ':id' => $groupId
            ]);

            // Remove existing font relations
            $removeQuery = "DELETE FROM font_group_fonts WHERE group_id = :group_id";
            $removeStmt = $this->db->prepare($removeQuery);
            $removeStmt->execute([':group_id' => $groupId]);

            // Insert new font relations
            $fontStmt = $this->db->prepare(
                "INSERT INTO font_group_fonts (group_id, font_id) VALUES (:group_id, :font_id)"
            );

            foreach ($groupData['fonts'] as $fontId) {
                $fontStmt->execute([
                    ':group_id' => $groupId,
                    ':font_id' => $fontId
                ]);
            }

            // Commit transaction
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            // Rollback in case of error
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Delete a font group
     * 
     * @param int $groupId Group identifier
     * @return bool Deletion success status
     */
    public function delete(int $groupId): bool {
        try {
            // Begin transaction
            $this->db->beginTransaction();

            // Delete font group relations
            $relationsQuery = "DELETE FROM font_group_fonts WHERE group_id = :group_id";
            $relationsStmt = $this->db->prepare($relationsQuery);
            $relationsStmt->execute([':group_id' => $groupId]);

            // Delete font group
            $groupQuery = "DELETE FROM font_groups WHERE id = :group_id";
            $groupStmt = $this->db->prepare($groupQuery);
            $groupStmt->execute([':group_id' => $groupId]);

            // Commit transaction
            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            // Rollback in case of error
            $this->db->rollBack();
            throw $e;
        }
    }
}