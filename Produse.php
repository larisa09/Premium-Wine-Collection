
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Produse</title>
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="styles.css">

<style>

.produs {
    flex: 1 1 300px;         
    max-width: 350px;
    border: 1px solid #ddd;
    border-radius: 15px;
    padding: 10px 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background-color: white;
    text-align: center;
    transition: transform 0.2s;
}

.produs:hover {
    transform: scale(1.02);
}

.produs img {
    width: 100%;
     height: 400px;   
    max-height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

    form{
    margin:0 2rem;
     }

     .search-container {
    display: flex;
    align-items: center;
    margin: 0 20px;
    flex-grow: 0.5;
    max-width: 400px;
    order: 1;
    margin-left: 20px;
}

.search-form {
    width: 100%;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 10px 20px 10px 40px;
    border: none;
    border-radius: 25px;
    outline: none;
    font-size: 1em;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    transition: all 0.3s ease;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-input:focus {
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
}

.search-button {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
}
.mesaj-cos {
    background-color: #d4edda;
    color: #155724;
    padding: 10px 20px;
    margin: 20px auto;
    width: fit-content;
    border-radius: 10px;
    border: 1px solid #c3e6cb;
    font-weight: bold;
    font-family: 'Poppins', sans-serif;
}


</style>
</head>
<body>
    <?php
    include 'Conexiune.php';
session_start();
if (isset($_SESSION['mesaj_cos'])) {
    echo "<div class='mesaj-cos'>" . htmlspecialchars($_SESSION['mesaj_cos']) . "</div>";
    unset($_SESSION['mesaj_cos']);
}
?>

<header>
   
<h1 class="title">Wine Shop</h1>

<form action="cautare.php" method="get">
    <input type="text" name="q" class="search-input" placeholder="Search...">
    <button type="submit" class="search-button">
        <i class="fas fa-search"></i>
    </button>
</form>

<nav class="navigare">
    <a href="index.php" class="buton-index">Home</a>
    <a href="Produse.php" class="buton-Produse">Wine Shop</a>
   <a href="AfisareProduseCos" class="buton-cos">Shopping Cart</a>



    <?php if (isset($_SESSION['nume'])): ?>
        <a href="profil.php">My Profile</a>
        <a href="Logout.php" class="buton-logout">Logout</a>
    <?php else: ?>
        <a href="Login.php" class="buton-login">Login</a>
        <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    <?php endif; ?>
</nav>

</header>

<div class="container3">
    <?php
    $sql = "SELECT * FROM produse";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
       echo "<div class='produs'>";
       echo "<a href='DetaliiProdus.php?id=" . $row["id"] . "'>";
       $imagine = $row["imagine"];
            $src = (str_starts_with($imagine, "http") || str_starts_with($imagine, "data:image/")) 
                   ? $imagine 
                   : "imagini/" . $imagine;
       echo "<img src='$src' alt='" . $row["nume"] . "' style='width:100%; height:auto;' onerror=\"this.src='placeholder.jpg'\">";
       echo "<h2>" . $row["nume"] . "</h2>";
       echo "</a>";
       echo "<p><strong>Price:</strong> " . $row["pret"] . " RON</p>";
       echo "<form method=\"POST\">";
       echo "<input type=\"hidden\" name=\"produs_id\" value=\"" . $row['id'] . "\">";
       echo '<button type="button" onclick="adaugaInCos(' . $row['id'] . ')">Adaugă în coș</button>';
       echo "</form>";
       echo"</div>";
        }
    } else {
        echo "Nu există produse disponibile.";
    }
   
    ?>
</div>
<div id="notificare" style="display:none; position:fixed; bottom:20px; left:50%; transform:translateX(-50%); background:#28a745; color:white; padding:10px 20px; border-radius:8px; font-family:'Poppins', sans-serif;">
</div>


<script>
function adaugaInCos(produs_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "AdaugareCos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var notificare = document.getElementById("notificare");
            notificare.textContent = xhr.responseText;
            notificare.style.display = "block";

            setTimeout(function() {
                notificare.style.display = "none";
            }, 3000);
        }
    };

    xhr.send("produs_id=" + produs_id);
}


  window.addEventListener('DOMContentLoaded', () => {
    const mesaj = document.querySelector('.mesaj-cos');
    if (mesaj) {
      setTimeout(() => {
        mesaj.style.transition = "opacity 0.5s ease";
        mesaj.style.opacity = '0';
        setTimeout(() => mesaj.remove(), 500); 
      }, 3000); 
    }
  });


</script>


</body>
</html>
