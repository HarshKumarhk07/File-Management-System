<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method!"]);
    exit;
}

// ✅ Check if file exists
if (empty($_FILES['fileUpload'])) {
    echo json_encode(["error" => "No file received! Check FormData name."]);
    exit;
}

$fileName = $_FILES['fileUpload']['name'];
$fileTmpName = $_FILES['fileUpload']['tmp_name'];
$fileType = $_FILES['fileUpload']['type'];
$fileSize = $_FILES['fileUpload']['size'];
$uploadDir = "uploads/";
$filePath = $uploadDir . basename($fileName);

// ✅ Updated Allowed File Types (Including Images)
$allowedTypes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',  // ✅ Image files
    'application/pdf', 'application/vnd.ms-excel', 
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
    'application/vnd.ms-powerpoint', 
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'text/plain'
];

// ✅ Check if file type is allowed
if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(["error" => "Invalid file type! Only images, PDFs, and office documents are allowed."]);
    exit;
}

// ✅ Move uploaded file to the uploads directory
if (move_uploaded_file($fileTmpName, $filePath)) {
    $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_type, file_size, file_path, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->bind_param("ssis", $fileName, $fileType, $fileSize, $filePath);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "File uploaded successfully!", "file_name" => $fileName]);
    } else {
        echo json_encode(["error" => "Error storing file in DB: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "File upload failed!"]);
}
?>
