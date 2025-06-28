<?php
include 'Conexiune.php';

if (isset($_GET['q'])) {
    $cautare = $conn->real_escape_string($_GET['q']);

    $sql = "SELECT * FROM produse WHERE nume LIKE '%$cautare%' OR descriere LIKE '%$cautare%'";
    $result = $conn->query($sql);

    echo "<h1>Rezultatele căutării pentru: <em>" . htmlspecialchars($cautare) . "</em></h1>";

    if ($result->num_rows > 0) {
        echo "<div class='container3'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='produs'>";
            echo "<img src='" . $row["imagine"] . "' alt='" . $row["nume"] . "'>";
            echo "<h2>" . $row["nume"] . "</h2>";
            echo "<p>" . $row["descriere"] . "</p>";
            echo "<p><strong>Preț:</strong> " . $row["pret"] . " RON</p>";
            echo "<form onsubmit='return false;'>";
            echo "<input type='hidden' name='produs_id' value='" . $row["id"] . "'>";
            echo "<button type='button' onclick='adaugaInCos(" . $row["id"] . ")'>Adaugă în coș</button>";
            echo "</form>";
            echo "</div>";
        }
        
        echo "</div>";
        
    } else {
        echo "<p>Nu am găsit produse care să corespundă criteriului de căutare.</p>";
    }
} else {
    echo "<p>Introduceți un termen pentru a căuta.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Produse</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
</head>
<body>

<header>
    <h1 class="title">Produse</h1>
    <nav class="navigare">
        <a href="index.php" class="buton-index">Home</a>
        <a href="Produse.php" class="buton-Produse">Wine Shop</a>
        <a href="AfisareProduseCos" class="buton-cos">Shopping Cart</a>
        <a href="Login.php" class="buton-login">Login</a>
            <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    </nav>
</header>
</body>
</html>