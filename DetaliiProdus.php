<?php
include 'Conexiune.php';
session_start();


$produs = null;
$id = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM produse WHERE id = $id";
    $rezultat = mysqli_query($conn, $sql);
    if ($rezultat && mysqli_num_rows($rezultat) > 0) {
        $produs = mysqli_fetch_assoc($rezultat);
    } else {
        die("The product wasa't found!");
    }
} else {
    die("The ID is not valid.");
}
?>


<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($produs['nume']); ?> - Premium Wine Collection</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
<style>
.container-detalii {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
    box-sizing: border-box;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    margin-bottom: 20px;
}

.produs-principal {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
}
.produs {
    width: 100%;
    max-width: 500px;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    padding: 20px;
    background-color: white;
    text-align: center;
    margin: 20px 0;
    font-family: 'Poppins', sans-serif;
    
}

.produs img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 12px;
    margin-bottom: 15px;
}

.recomandari {
    width: 100%;
    margin-top: 3rem;
    text-align: center;
    padding: 2rem 0;
   
}

.recomandari h2 {
    font-size: 1.8rem;
    margin-bottom: 2rem;
    color: #333;
}

.lista-recomandari {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    justify-items: center;
}

.produs-recomandat {
    width: 100%;
    max-width: 300px;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    padding: 20px;
    background-color: white;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.produs-recomandat:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.produs-recomandat img {
    width: 100%;
    height: 200px; /* Increased from 160px */
    object-fit: contain;
    border-radius: 8px;
    margin-bottom: 15px;
}




button {
        background-color:none; /* Purple color */
    color: #96645e;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-family: 'Poppins', sans-serif;
    margin-top: 15px;
}

button:hover {
    background-color:none;
}

#notificare {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #4caf50;
    color: white;
    padding: 12px 20px;
    border-radius: 6px;
    z-index: 1000;
}
</style>
    </style>
</head>
<body>
    <header>
        <h1 class="title">About Product</h1>
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

    <div class="container-detalii">
        <div class="produs-principal">
        <div class="produs">
            <h1><?php echo htmlspecialchars($produs['nume']); ?></h1>
          <?php
            $imagine = $produs["imagine"];
            $src = (str_starts_with($imagine, "http") || str_starts_with($imagine, "data:image/"))? $imagine: "imagini/" . $imagine;
            echo "<img src='$src' alt='" . htmlspecialchars($produs["nume"]) . "' style='width:100%; height:auto;' onerror=\"this.src='placeholder.jpg'\">";
          ?>         
            <p><strong>Price:</strong> <?php echo htmlspecialchars($produs['pret']); ?> RON</p>
            <p><strong>About:</strong> <?php echo htmlspecialchars($produs['descriere']); ?></p>
            <button type="button" onclick="adaugaInCos(<?php echo $produs['id']; ?>)">Add to Cart</button>
        </div>
        </div>

        <div class="recomandari">
            <h2>Recommended products</h2>
            <div class="lista-recomandari">
                
                <?php
                
                $recomandari = mysqli_query($conn, "SELECT * FROM produse WHERE id != $id ORDER BY RAND() LIMIT 3");

                if ($recomandari && mysqli_num_rows($recomandari) > 0) {
                    while ($row = mysqli_fetch_assoc($recomandari)) {
                       echo '<a href="DetaliiProdus.php?id=' . $row['id'] . '" class="produs-recomandat-link">';
                       echo '<div class="produs-recomandat">';
                       $imagine = $row["imagine"];
            $src = (str_starts_with($imagine, "http") || str_starts_with($imagine, "data:image/")) 
                   ? $imagine 
                   : "imagini/" . $imagine;

            echo "<img src='$src' alt='" . $row["nume"] . "' style='width:100%; height:auto;' onerror=\"this.src='placeholder.jpg'\">";
                       echo '<p>' . htmlspecialchars($row['nume']) . '</p>';
                       echo '<p><strong>Price:</strong> ' . htmlspecialchars($row['pret']) . ' RON</p>';
                       echo '</div>';
                       echo '</a>';

                    }
                } else {
                    echo '<p>Nu există produse recomandate momentan.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <div id="notificare"></div>

<script>
        
function adaugaInCos(produsId) {
    const formData = new FormData();
    formData.append('produs_id', produsId);

    fetch('AdaugareCos.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.text())
    .then(data => {
        const notificare = document.getElementById('notificare');
        notificare.innerText = data;
        notificare.style.display = 'block';
        setTimeout(() => {
            notificare.style.display = 'none';
        }, 3000);
    })
    .catch(error => {
        console.error('Eroare la adăugare în coș:', error);
    });
}
</script>
</body>
</html>
<?php $conn->close(); ?>