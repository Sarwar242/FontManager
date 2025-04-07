<?php
namespace FontManager\Model;

use FontManager\Core\Database;
use PDO;

class FontModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $fontData): int {
        $query = "INSERT INTO fonts 
            (filename, original_name, font_family, font_style, upload_path) 
            VALUES (:filename, :original_name, :family, :style, :path)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':filename' => $fontData['filename'],
            ':original_name' => $fontData['originalName'],
            ':family' => $fontData['font_family'] ?? 'Unknown',
            ':style' => $fontData['details']['style'] ?? 'Regular',
            ':path' => $fontData['filepath']
        ]);

        return $this->db->lastInsertId();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM fonts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById(int $fontId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM fonts WHERE id = :id");
        $stmt->execute([':id' => $fontId]);
        return $stmt->fetch() ?: null;
    }

    public function delete(int $fontId): bool {
        $stmt = $this->db->prepare("DELETE FROM fonts WHERE id = :id");
        return $stmt->execute([':id' => $fontId]);
    }
}