<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: AdminDashboard.php");
    exit;
}

include 'Conexiune.php';

$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['imagine']) || $_FILES['imagine']['error'] !== UPLOAD_ERR_OK) {
        $mesaj = "Eroar while loading: ";
        switch ($_FILES['imagine']['error'] ?? 4) { 
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $mesaj .= "File to big.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $mesaj .= "Partial loading.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $mesaj .= "No file selected.";
                break;
            default:
                $mesaj .= "Unknown error.";
        }
    } else {
        $nume = $_POST['nume'];
        $descriere = $_POST['descriere'];
        $pret = $_POST['pret'];

        
        $target_dir = "imagini/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        
        $file_tmp = $_FILES['imagine']['tmp_name'];
        $file_name = basename($_FILES['imagine']['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp']; 

        if (!in_array($file_type, $allowed)) {
            $mesaj = "Format imagine invalid. Acceptăm doar JPG, JPEG, PNG, WEBP sau GIF.";
        } else {
           
            $image_info = @getimagesize($file_tmp); 
            if ($image_info === false) {
                $mesaj = "Fișierul nu este o imagine validă.";
            } else {
              
                $new_filename = uniqid('img_', true) . '.' . $file_type;
                $target_file = $target_dir . $new_filename;

                
                if (move_uploaded_file($file_tmp, $target_file)) {
                    
                    $stmt = $conn->prepare("INSERT INTO produse (nume, descriere, pret, imagine) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssds", $nume, $descriere, $pret, $new_filename);
                    
                    if ($stmt->execute()) {
                        header("Location: AdminDashboard.php?success=1");
                        exit;
                    } else {
                        $mesaj = "Error while saveing the produsct: " . $conn->error;
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                    }
                } else {
                    $mesaj = "Eroare la mutarea fișierului. Verificați permisiunile sau spațiul pe disc.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă Produs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        

        .form-container {
            background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        }

        
  
       
    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #6b002b;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #800000;
        font-size: 15px;
    }

         input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    .buttons {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-top: 30px;
        gap: 20px;
    }

    button {
        background-color: #6b1b1b;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #4e1111;
    }

     .center {
            text-align: center;
        }

        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #6b002b;
            text-decoration: none;
        }

    .cancel {
        text-align: center;
        margin-top: 20px;
    }

    .cancel a {
        color: #6b002b;
        text-decoration: none;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .cancel a:hover {
        background-color: rgba(107, 0, 43, 0.1);
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

<div class="form-container">
    <h2>Add Product</h2>

    <?php if (!empty($mesaj)) echo "<div class='error'>$mesaj</div>"; ?>

    <form action="AdaugaProdusAdmin.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
        <label for="nume">Name:</label>
        <input type="text" name="nume" required>
        </div>

        <div class="form-group">
        <label for="descriere">About:</label>
        <textarea name="descriere" rows="4" required></textarea>
        </div>

        <div class="form-group">
        <label for="pret">Price (RON):</label>
        <input type="number" name="pret" step="0.01" required>
        </div>

        <div class="form-group">
        <label for="imagine">Imagine:</label>
        <input type="file" name="imagine" id="imagine" accept="image/*" required>
       
        </div>

        <div class="center">
            <button type="submit" class="btn">Add</button>
        </div>    
           <div class="cancel">
        <a href="AdminDashboard.php">Cancel</a>
        </div>
    </form>
</div>

<script>
    const imagineInput = document.getElementById('imagine');
    const preview = document.getElementById('preview');

    imagineInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            preview.style.display = 'block';
            preview.src = URL.createObjectURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
</body>
</html>
