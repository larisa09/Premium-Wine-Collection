<?php
include 'Conexiune.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nume = $_POST['nume'];
    $prenume = $_POST['prenume'];
    $email = $_POST['email'];
    $parola = $_POST['parola'];
    $parola=md5($parola);
    $admin=isset ($_POST['admin']) ? 1:0;

$sql = "INSERT INTO utilizatori (nume, prenume, email, parola, admin) 
VALUES ('$nume', '$prenume' ,'$email', '$parola','$admin')";
    
if ($conn->query($sql) === TRUE) {
        echo "Înregistrare reușită!";

 $sql = "SELECT id FROM utilizatori WHERE email = '$email' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
    
    } else {
        echo "Email sau parolă incorectă.";
    }

    } else {
        echo "Eroare la înregistrare: " . $conn->error;
    }
$_SESSION['id'] = $user_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    </head>
    <style>
        .checkbox-group {
    display: flex;
    align-items: center;
    margin-top: 15px;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    font-size: 1rem;
    cursor: pointer;
}

.checkbox-group input[type="checkbox"] {
    margin-right: 10px;
    transform: scale(1.2);
}

    </style>
<body>

<header>
    <h1 class="title">Register</h1>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
   <nav class="navigare">
        <a href="index.php" class="buton-index">Home</a>
        <a href="Produse.php" class="buton-Produse">Wine Shop</a>
        <a href="AfisareProduseCos" class="buton-cos">Shopping Cart</a>
        <a href="Login.php" class="buton-login">Login</a>
            <a href="InregistrareUtilizator.php" class="buton-register">Register</a>
    </nav>


    <?php  
 
 
?>
</header>

    <div class="container1" id="signup">
        <h1 class="form-title">REGISTER</h1>
        <form method="post" action="InregistrareUtilizator.php">
            
        <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="prenume" id="prenume" placeholder="First Name" required></input>
                <label for="prenume">First Name</label>
            </div>
                
            <div class="input-group">

                    <i class="fas fa-user"></i>
                    <input type="text" name="nume" id="nume" placeholder="Last Name" required></input>
                    <label for="nume">Last Name</label>
                </div>
                <div class="input-group">

                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="Email" required></input>
                    <label for="email">Email</label>
                </div>

                <div class="input-group">

                    <i class="fas fa-lock"></i>
                    <input type="parola" name="parola" id="parola" placeholder="Password" required></input>
                    <label for="parola">Password</label>
                </div>
                <div class="input-group checkbox-group">
           <label for="admin">
                <input type="checkbox" name="admin" id="admin">
                Admin
               </label>
            </div>
                <input type="submit" class="btn" value="Sign Up" name="singUp">

        </form>
        
        <div class="links">
            <p>Alredy have an Account?</p>
                <button id="singInButton"onclick="window.location.href='Login.php'">Sign In</button>
          
        </div> 
    
    
    
    </div>
    
</body>
</html>