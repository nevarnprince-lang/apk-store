<?php
header('Content-Type: application/json');

$dataPath = 'data/data.json';
$uploadDir = 'data/';

// Initialize JSON file if it doesn't exist
if (!file_exists($dataPath)) {
    file_put_contents($dataPath, json_encode([]));
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// --- GET APPS ---
if ($method === 'GET' && $action === 'getApps') {
    echo file_get_contents($dataPath);
    exit;
}

// --- UPLOAD APP ---
if ($method === 'POST' && $action === 'upload') {
    try {
        $id = time();
        $name = $_POST['name'];
        $dev = $_POST['dev'];
        $version = $_POST['version'];
        $desc = $_POST['desc'];

        // Handle Icon Upload
        $iconName = 'icon_' . $id . '_' . basename($_FILES['icon']['name']);
        $iconPath = $uploadDir . $iconName;
        
        // Handle APK Upload
        $apkName = 'app_' . $id . '_' . basename($_FILES['apk']['name']);
        $apkPath = $uploadDir . $apkName;

        if (move_uploaded_file($_FILES['icon']['tmp_name'], $iconPath) && 
            move_uploaded_file($_FILES['apk']['tmp_name'], $apkPath)) {
            
            $apps = json_decode(file_get_contents($dataPath), true);
            
            $newApp = [
                'id' => $id,
                'name' => $name,
                'dev' => $dev,
                'version' => $version,
                'desc' => $desc,
                'size' => round($_FILES['apk']['size'] / (1024 * 1024), 2) . ' MB',
                'icon' => $iconPath,
                'apk' => $apkPath
            ];

            $apps[] = $newApp;
            file_put_contents($dataPath, json_encode($apps, JSON_PRETTY_PRINT));
            
            echo json_encode(['success' => true, 'message' => 'App brewed successfully!']);
        } else {
            throw new Exception('Failed to move uploaded files.');
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
