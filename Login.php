<?php
session_start();
include 'Conexiune.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $parola = md5($_POST['parola']);

    $sql = "SELECT id, nume, prenume, admin FROM utilizatori WHERE email = '$email' AND parola = '$parola'";
    
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        $_SESSION['utilizator_id'] = $user['id'];
        $_SESSION['nume'] = $user['nume'];
        $_SESSION['prenume'] = $user['prenume'];
        $_SESSION['admin'] = $user['admin'];

        if (isset($_GET['redirect']) && $_GET['redirect'] === 'finalizare') {
            header("Location: FinalizareComanda.php");
            exit();
        } else {
            if ($user['admin'] == 1) {
                header("Location: AdminDashboard.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Email or password incorrect!</p>";
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'auth_required') {
    echo "<p style='color: red; text-align: center;'>Trebuie să fii autentificat pentru a finaliza comanda.</p>";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<header>
   <h1 class="title">Login</h1>
    <nav class="navigare">
        <a href="index.php" class="buton-index">Home</a>
        <a href="Produse.php" class="buton-Produse">Wine Shop</a>
        <a href="AfisareProduseCos.php" class="buton-cos">Shopping Cart</a>
        <a href="Login.php" class="buton-login">Login</a>
        <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    </nav>
</header>

<div class="container1" id="signIn">
    <h1 class="form-title">LOGIN</h1>
    <form method="post" action="">
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="parola" id="parola" placeholder="Password" required>
            <label for="parola">Password</label>
        </div>

        <p class="recover">
            <a href="ResetareParola.php">Reset Password</a>
        </p>

        <input type="submit" class="btn" value="Login" name="logIn">
    </form>


    <div class="links">
        <p>Don't have an Account yet?</p>
        <button id="signUpButton" onclick="window.location.href='InregistrareUtilizator.php'">Sign Up</button>
    </div>
</div>
</body>
</html>
