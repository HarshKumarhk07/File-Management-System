<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
include 'db_connect.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT file_name, status FROM uploaded_files");
$files = [];

while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

echo json_encode($files);
?>
