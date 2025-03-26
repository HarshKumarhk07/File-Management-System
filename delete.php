<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'db_connect.php';  // ✅ Corrected include file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fileName = $_POST['fileName'];

    // Mark the file as deleted instead of removing it
    $sql = "UPDATE uploaded_files SET status='deleted' WHERE file_name=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $fileName);
    
    if ($stmt->execute()) {
        echo "File moved to trash!";
    } else {
        echo "Error deleting file: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request!";
}
?>

