<?php
require_once __DIR__ . '/../vendor/autoload.php';

use FontManager\Controller\FontController;
use FontManager\Controller\FontGroupController;

// Simple routing
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
    // Fonts API
    if (preg_match('#^/api/fonts(/(\d+))?$#', $requestUri, $matches)) {
        $fontId = $matches[2] ?? null;
        $controller = new FontController();

        switch ($requestMethod) {
            case 'GET':
                if ($fontId) {
                    $controller->getFontDetails($fontId);
                } else {
                    $controller->listFonts();
                }
                break;
            case 'POST':
                $controller->upload(
                    $_FILES,
                $_POST['name'],
                $_POST['font_family']);
                break;
            case 'DELETE':
                if ($fontId) {
                    $controller->delete($fontId);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed']);
        }
    }
    // Font Groups API
    elseif (preg_match('#^/api/font-groups(/(\d+))?$#', $requestUri, $matches)) {
        $groupId = $matches[2] ?? null;
        $controller = new FontGroupController();

        switch ($requestMethod) {
            case 'GET':
                if ($groupId) {
                    // Implement get single group
                } else {
                    $controller->listGroups();
                }
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $controller->create($data);
                break;           
                
            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                if ($groupId) {
                    $controller->update((int)$groupId, $data);
                }
                break;
            case 'DELETE':
                if ($groupId) {
                    $controller->delete($groupId);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed']);
        }
    }
    else {
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server Error',
        'message' => $e->getMessage()
    ]);
}