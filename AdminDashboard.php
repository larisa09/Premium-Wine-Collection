    <?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: AdminDashboard.php");
    exit;
}

echo "<div class='container3'>";

include 'Conexiune.php';

$sql = "SELECT * FROM produse";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='produs'>";

        $imagine = $row["imagine"];
        $src = (str_starts_with($imagine, "http") || str_starts_with($imagine, "data:image/")) 
               ? $imagine 
               : "imagini/" . $imagine;

        echo "<img src='$src' alt='" . $row["nume"] . "' style='width:100%; height:auto;' onerror=\"this.src='placeholder.jpg'\">";
        echo "<h2>" . $row["nume"] . "</h2>";
        echo "<p>" . $row["descriere"] . "</p>";
        echo "<p><strong>Price:</strong> " . $row["pret"] . " RON</p>";
        echo "<button onclick='ModificaProdus(" . $row["id"] . ")'>Change</button>";
        echo " / ";
        echo "<button onclick='StergeProdus(" . $row["id"] . ")'>Delete</button>";
        echo "</div>";
    }
} else {
    echo "Nu există produse disponibile.";
}

 echo"<img id='preview' src= '' alt='Preview' style='display: none; margin-top:10px; max-width: 100%;'>";
echo "</div>";
?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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

</style>
</head>

<body>

    <header>
    <h1 class="title1">Premium Wine Collection</h1>
        <nav class="navigare">
        <a href="AdminDashboard.php">Dashboard</a>
        <a href="AdaugaProdusAdmin.php">Add Product</a>
       <a href="VeziComenzi.php">See Orders</a>
        <a href="logout.php">Logout</a>
    </nav>
    </header>


<script>
function StergeProdus(id) {
    if (confirm("Are you sure you want to detete this product?")) {
        fetch('StergeProdusAdmin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            location.reload();
        })
        .catch(error => {
            console.error('Eroare:', error);
            alert('Error');
        });
    }
}

function ModificaProdus(id) {
    window.location.href = "ModificareProdusAdmin.php?id=" + id;
}
</script>
</body>
</html>
