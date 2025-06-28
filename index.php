<?php
include 'Conexiune.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name= "viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Premium Wine Collection</title>
    <link rel="stylesheet"  href="styles.css">
 <style>
    footer {
    background-color: #f8f8f8;
    color: #333;
    font-family: 'Poppins', sans-serif;
    padding: 40px 0 0;
    border-top: 1px solid #e7e7e7;
}

.containerF {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    padding: 0 20px;
}

.footer-content h3 {
    color: #222;
    font-size: 18px;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-content h3::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background-color: #c04034;
}

.footer-content p {
    margin: 10px 0;
    line-height: 1.6;
}

.footer-content ul {
    list-style: none;
    padding: 0;
}

.footer-content ul li {
    margin-bottom: 10px;
}

.footer-content ul li a {
    color: #555;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-content ul li a:hover {
    color: #c04034;
}

.col-4 p{
    font-size: 14px;
}

 </style>   

</head>
<body>

    <header>
    <h1 class="title1">Premium Wine Collection</h1>
        
        <?php  
session_start();
?>

<nav class="navigare">
    <a href="index.php" class="buton-index">Home</a>
    <a href="Produse.php" class="buton-Produse">Wine Shop</a>
    <a href="AfisareProduseCos" class="buton-cos">Shopping Cart</a>
    <?php if (isset($_SESSION['nume'])): ?>
        <span class="welcome-msg">Welcome <b><?php echo htmlspecialchars($_SESSION['nume']); ?></b></span>
        <a href="profil.php">My Profile</a>
        <a href="Logout.php">Logout</a>
     <?php else: ?>
        <a href="Login.php" class="buton-login">Login</a>
        <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    <?php endif; ?>
</nav>


    </header>
<div class="container2">
    <div class="row">
        <div class="col-2">
            <h1>Where Passion Meets Prestige!</h1>
            <p>Discover an exclusive selection of handpicked wines, crafted for those who truly appreciate refined taste.<br> At Premium Wine Collection, every bottle tells a story of passion, tradition, and prestige.</p>
            <a href="Produse.php" class="button" >Explore Now &#8594;</a>
        </div>
        <div class="col-2">
            <img src="poza_1.png">
        </div>
</div>

<!----------------3img------------>
<div class="categories">
  <div class="small-container">
    <div class="row">
      <div class="col-3">
        <img src="plant-grape-vine-vineyard-wine-fruit-1377579-pxhere.com" alt="Vineyard">
      </div>
      <div class="col-3">
        <img src="vie.jpeg" alt="Vie">
      </div>
      <div class="col-3">
        <img src="struguri_albi.jpeg" alt="Struguri albi">
      </div>
    </div>
  </div>
</div>

<!-----------------4img-------->
<div class="small-container">
    <h2 class="subtitle">Best Sellers</h2>
    <div class="row">
        <?php
        // 1. Conectare la baza de date
        $conn = new mysqli("localhost", "root", "", "licenta");

        if ($conn->connect_error) {
            die("Conexiunea a eșuat: " . $conn->connect_error);
        }

   
$sql = "SELECT * FROM produse LIMIT 4";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-4">';
        $imagine = $row["imagine"];
        $src = (str_starts_with($imagine, "http") || str_starts_with($imagine, "data:image/")) 
               ? $imagine 
               : "imagini/" . $imagine;
        echo "<a href='DetaliiProdus.php?id=" . $row["id"] . "'>";
        echo "<img src='$src' alt='" . htmlspecialchars($row["nume"]) . "' style='width:100%; height:auto;' onerror=\"this.src='placeholder.jpg'\">";
        echo "<h4>" . htmlspecialchars($row["nume"]) . "</h4>";
        echo "</a>";

        echo '<p>' . number_format($row["pret"], 2) . ' RON</p>';
        echo '</div>';
    }
} else {
    echo "<p>Nu s-au găsit produse.</p>";
}
$conn->close();
        ?>
    </div>
</div>


<footer>
    <div class="containerF">
        <div class="footer-content">
            <h3>Contact Us</h3>
            <p>Email:premiumwine@gmail.com</p>
            <p>Phone:+040759841267</p>
            <p>Addresss:Str Vinurilor nr.45</p>
        </div>
        <div class="footer-content">
            <h3>Quick Links</h3>
            <ul class="list">
            <li><a href="index.php">Home</a></li>
            <li><a href="Produse.php">Wine Shop</a></li>
            <li><a href="AfisareProduseCos">Shopping Cart</a></li>
            <li><a href="Login.php">Login</a></li>
            <li><a href="InregistrareUtilizator.php">Register</a></li>
            </ul>
        </div>
        <div class="footer-content">
            <h3>Follow Us</h3>
            <ul class="social-icons">
            <li><a href=""><i class="fab fa-facebook"></i></a></li>
            <li><a href=""><i class="fab fa-twitter"></i></a></li>
            <li><a href=""><i class="fab fa-instagram"></i></a></li>
            </ul>
        </div>
    </div>
    
</footer>
</body>
</html>

