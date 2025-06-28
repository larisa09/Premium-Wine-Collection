<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: AdminDashboard.php");
    exit;
}

include 'Conexiune.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comanda_id'], $_POST['status'])) {
    $id = $_POST['comanda_id'];
    $status_nou = $_POST['status'];

    $stmt = $conn->prepare("UPDATE comenzi SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status_nou, $id);
    $stmt->execute();
    $stmt->close();
}


$sql = "SELECT * FROM comenzi";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
    <style>
       

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #6b002b;
            color: white;
        }

        form {
            margin: 0;
        }

        select, button {
            padding: 5px 10px;
        }

        .btn-update {
            background-color: #6b002b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-update:hover {
            background-color: #4e0020;
        }
    </style>
</head>
<body>
       <header>
    <h1 class="title1">Orders</h1>
        <nav class="navigare">
        <a href="AdminDashboard.php">Dashboard</a>
        <a href="AdaugaProdusAdmin.php">Add Product</a>
       <a href="VeziComenzi.php">See orders</a>
        <a href="logout.php">Logout</a>
    </nav>
    </header>


    <table>
        <tr>
            <th>ID</th>
            <th>ID User</th>
            <th>Order Number</th>
            <th>Status</th>
            <th>Update</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['utilizator_id'] ?></td>
            <td><?= $row['nr_comanda'] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="comanda_id" value="<?= $row['id'] ?>">
                    <select name="status">
                  <option value="Placed" <?= $row['status'] == 'Placed' ? 'selected' : '' ?>>Placed</option>
                  <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                  <option value="Shipped" <?= $row['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                  <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                  <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </td>
            <td>
                    <button type="submit" class="btn-update">Save</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
