<?php
session_start();
require_once 'Conexiune.php';
$mesaj="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $parola_noua = trim($_POST["parola_noua"]);
    $confirmare_parola = trim($_POST["confirmare_parola"]);

    if ($parola_noua != $confirmare_parola) {
        $mesaj = "Parolele nu coincid!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM utilizatori WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $hash = md5($parola_noua); // criptare cu MD5

            $stmt->close();
            $update = $conn->prepare("UPDATE utilizatori SET parola = ? WHERE email = ?");
            $update->bind_param("ss", $hash, $email);
            if ($update->execute()) {
                $mesaj = "Parola a fost resetată cu succes.";
            } else {
                $mesaj = "Eroare la actualizarea parolei.";
            }
            $update->close();
        } else {
            $mesaj = "Emailul nu a fost găsit.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
     <meta charset="UTF-8">
    <title>Recover Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  
          
</head>
<body>
    <header>
<h1 class="title1">Reset Password</h1>
<nav class="navigare">
    <a href="index.php" class="buton-index">Home</a>
    <a href="Produse.php" class="buton-Produse">Wine Shop</a>
    <a href="AfisareProduseCos" class="buton-cos">Shopping Cart</a>


    </header>
<div class="container1">
    <h1 class="form-title">Reset Password</h1>
    <form method="post" action="">
    <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <label for="email">Email</label>
    </div>
    <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="parola_noua" id="parola_noua" placeholder="New Password" required>
        <label for="parola_noua">New Password</label>
    </div>
    <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="confirmare_parola" id="confirmare_parola" placeholder="Confirm Password" required>
        <label for="confirmare_parola">Confirm Password</label>
    </div>
    
    <button type="submit" class="btn">Reset Password</button>
</form>

    <?php if ($mesaj): ?>
        <div class="mesaj"><?php echo htmlspecialchars($mesaj); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
