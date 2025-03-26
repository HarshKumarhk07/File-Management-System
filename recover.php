<?php
include 'db_connect.php';  // ✅ Corrected include file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fileName = $_POST['fileName'];

    // Recover the file by changing status back to 'active'
    $sql = "UPDATE uploaded_files SET status='active' WHERE file_name=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $fileName);
    
    if ($stmt->execute()) {
        echo "File recovered successfully!";
    } else {
        echo "Error recovering file: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request!";
}
?>
