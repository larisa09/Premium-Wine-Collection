<?php
session_start();
include 'Conexiune.php';

// Verifică dacă coșul e gol
if (!isset($_SESSION['cos']) || empty($_SESSION['cos'])) {
    echo "Coșul tău este gol.";
    exit();
}

// Asigură-te că vectorul de cantități există
if (!isset($_SESSION['cantitati'])) {
    $_SESSION['cantitati'] = array();
}

// Elimină duplicatele și pregătește ID-urile
$produs_ids = array_unique($_SESSION['cos']);

if (empty($produs_ids)) {
    echo "Coșul tău este gol.";
    exit();
}

$produs_ids_str = implode(",", array_map('intval', $produs_ids)); 

$sql = "SELECT * FROM produse WHERE id IN ($produs_ids_str)";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">


<style>
.produs-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    width: 400px;
    margin: 30px auto;
    padding: 20px;
    text-align: center;
}

.produs-container img {
    width: 100%;
    height: auto;
    border-radius: 12px;
}

.produs-container h2 {
    color: #5d2c90;
    font-size: 22px;
    margin-top: 15px;
}

.produs-container p {
    margin: 10px 0;
    color: #333;
}

.quantity-control {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
}

.quantity-btn {
    padding: 6px 12px;
    font-size: 18px;
    border: none;
    background-color: #a76ecb;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    margin: 0 5px;
}

.quantity-btn:hover {
    background-color: #8b5bb3;
}

.quantity-input {
    width: 40px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 4px;
    font-size: 16px;
    background-color: #f5f5f5;
}

button[type="submit"] {
    font-family: 'Poppins', sans-serif;
}

form[action="FinalizareComanda.php"] {
    text-align: center;
}

form[action="FinalizareComanda.php"] button {
    margin-top: 10px;
    padding: 12px 24px;
    font-size: 18px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s ease;
}

form[action="FinalizareComanda.php"] button:hover {
    background: #218838;
}

.total-final-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.total-final-card {
    background-color: #fff;
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    text-align: center;
    width: 300px;
}

.total-general {
    font-size: 20px;
    font-weight: bold;
    color: #5d2c90;
    margin-bottom: 15px;
}

.total-final-card button {
    background-color: #28a745;
    color: white;
    padding: 12px 24px;
    font-size: 16px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.total-final-card button:hover {
    background-color: #218838;
}


</style>
</head>


<body>

<header>
  

<h1 class="title">Wine Shop</h1>



<nav class="navigare">
    <a href="index.php" class="buton-index">Home</a>
    <a href="Produse.php" class="buton-Produse">Wine Shop</a>
    <a href="AfisareProduseCos.php" class="buton-cos">Shopping Cart</a>

    <?php if (isset($_SESSION['nume'])): ?>
        <a href="profil.php">My Profile</a>
        <a href="Logout.php" class="buton-logout">Logout</a>
    <?php else: ?>
        <a href="Login.php" class="buton-login">Login</a>
        <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    <?php endif; ?>
</nav>


</header>


    <?php
    echo '<div class="container3">';
    if ($result && $result->num_rows > 0) {
        $total = 0;
        while($row = $result->fetch_assoc()) {
            $produs_id = $row['id'];
            $cantitate = $_SESSION['cantitati'][$produs_id] ?? 1;
            $subtotal = $row['pret'] * $cantitate;
            $total += $subtotal;
            
            echo "<div class='produs-container'>";
            echo "<h2>" . htmlspecialchars($row["nume"]) . "</h2>";

            $imagine = $row["imagine"];
            $src = (str_starts_with($imagine, "http") || str_starts_with($imagine, "data:image/")) 
                   ? $imagine 
                   : "imagini/" . $imagine;

            echo "<img src='$src' alt='" . $row["nume"] . "' style='width:100%; height:auto;' onerror=\"this.src='placeholder.jpg'\">";
            echo "<p>Price: " . htmlspecialchars($row["pret"]) . " RON</p>";
            echo "<form method='POST' action='ModificareCantitate.php' class='quantity-control'>";
            echo "<input type='hidden' name='produs_id' value='{$produs_id}'>";
            echo "<button type='submit' name='actiune' value='decrease' class='quantity-btn'>-</button>";
            echo "<input type='text' value='{$cantitate}' class='quantity-input' readonly>";
            echo "<button type='submit' name='actiune' value='increase' class='quantity-btn'>+</button>";
            echo "</form>";


            
            echo "<p>Subtotal: " . $subtotal . " RON</p>";
            echo "</div>";
        }
echo'</div>';
        // Afișăm totalul general
       echo "
<div class='total-final-wrapper'>
    <div class='total-final-card'>
        <div class='total-general'>Final Price: {$total} RON</div>
        <form action='FinalizareComanda.php' method='post'>
            <input type='hidden' name='total_comanda' value='{$total}'>
            <button type='submit'>Complete Order</button>
        </form>
    </div>
</div>";
}
    ?>

</body>
</html>