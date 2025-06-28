<?php
session_start();
include 'Conexiune.php';

if (!isset($_SESSION['utilizator_id'])) {
    header("Location: Login.php?msg=auth_required");
    exit();
}

$utilizator_id = $_SESSION['utilizator_id'];
$nume = $_SESSION['nume'] ?? '';
$prenume = $_SESSION['prenume'] ?? '';

$sql = "
    SELECT 
        comenzi.id, 
        comenzi.nr_comanda, 
        comenzi.status, 
        detalii_comenzi.data_comanda
    FROM comenzi
    LEFT JOIN detalii_comenzi 
        ON comenzi.utilizator_id = detalii_comenzi.utilizator_id
    WHERE comenzi.utilizator_id = $utilizator_id
    GROUP BY comenzi.id
    ORDER BY detalii_comenzi.data_comanda DESC
";
$result = $conn->query($sql);


?>


<!DOCTYPE html>
<html lang="ro">
<head>
<head>
    <meta charset="UTF-8">
    <meta name= "viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>My Profile</title>
    <link rel="stylesheet"  href="styles.css">
</head>
    <style>
     
        .profil-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #7f162b;
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 30px;
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<header>
<h1 class="title1">My Profile</h1>
<nav class="navigare">
    <a href="index.php" class="buton-index">Home</a>
    <a href="Produse.php" class="buton-Produse">Wine Shop</a>
    <a href="AfisareProduseCos" class="buton-cos">Shopping Cart</a>

    <?php if (isset($_SESSION['nume'])): ?>
    <?php else: ?>
        <a href="Login.php" class="buton-login">Login</a>
        <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    <?php endif; ?>
</nav>


    </header>

<div class="profil-container">
   
    <div class="info"><strong>Last Name:</strong> <?php echo htmlspecialchars($nume); ?></div>
    <div class="info"><strong>First Name:</strong> <?php echo htmlspecialchars($prenume); ?></div>

    <h3>My Orders</h3>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($comanda = $result->fetch_assoc()): ?>
                    <tr>
                         <td>#<?php echo htmlspecialchars($comanda['nr_comanda']); ?></td>
                    <td><?php echo date("d-m-Y", strtotime($comanda['data_comanda'])); ?></td>
                    <td><?php echo htmlspecialchars($comanda['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">You don't have any orders.</p>
    <?php endif; ?>

    <a href="Logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
