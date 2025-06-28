<?php
session_start();
if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    echo "Acces interzis.";
    exit;
}

include 'Conexiune.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

  
    $sql = "SELECT imagine FROM produse WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['imagine']) && !filter_var($row['imagine'], FILTER_VALIDATE_URL)) {
            $imagePath = "imagini/" . $row['imagine'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }
    


    $sql = "DELETE FROM produse WHERE id = $id";
   
   
    if ($conn->query($sql)) {
        echo "Produs șters cu succes.";
    } else {
        echo "Eroare la ștergere: " . $conn->error;
    }
} else {
    echo "ID invalid.";
}

$conn->close();
?>