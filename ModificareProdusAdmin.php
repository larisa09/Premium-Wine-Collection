<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: AdminDashboard.php");
    exit;
}

include 'Conexiune.php';

if (!isset($_GET['id'])) {
    echo "Produsul nu a fost specificat.";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM produse WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "Produsul nu a fost găsit.";
    exit;
}

$row=$result-> fetch_assoc();

?>

<!DOCTYPE html>
<html lang="ro">
<head>
      <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>

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

    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        background-color: #f9f9f9;
        transition: border 0.3s;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    textarea:focus {
        border-color: #6b002b;
        outline: none;
        background-color: #fff;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
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

#drop-area {
    border: 2px dashed #6b002b;
    padding: 20px;
    text-align: center;
    border-radius: 10px;
    background-color: #fefefe;
    cursor: pointer;
}

#drop-area.hover {
    background-color: #f3e6ec;
}

#drop-area p {
    margin-bottom: 10px;
    color: #6b002b;
    font-weight: 500;
}
</style>

</head>
<body>
    
 <header>
    <h1 class="title1">Change Product</h1>
        <nav class="navigare">
        <a href="AdminDashboard.php">Dashboard</a>
        <a href="AdaugaProdusAdmin.php">Add Product</a>
       <a href="VeziComenzi.php">See Orders</a>
        <a href="logout.php">Logout</a>
    </nav>
    </header>

    <div class="form-container">
    <form id="modificaProdusForm" method="post" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="imagine_veche" value="<?php echo htmlspecialchars($row['imagine']); ?>">

        <div class="form-group">
        <label for="nume">Name:</label>
        <input type="text" name="nume" value="<?php echo htmlspecialchars($row['nume']); ?>" required><br>
        </div>

        <div class="form-group">
        <label for="descriere">About:</label>
        <textarea name="descriere" required><?php echo htmlspecialchars($row['descriere']); ?></textarea><br>
        </div>

        <div class="form-group">
        <label for="pret">Price (RON):</label>
        <input type="number" name="pret" step="0.01" value="<?php echo $row['pret']; ?>" required><br>
        </div>

        <div class="form-group">
    <label for="imagine">Imagine Product:</label>
    <div id="drop-area">
        <p>Drop or paste an imagine here</p>
        <input type="file" name="imagine" id="imagine" accept="image/*">
        <img id="preview" src="<?php echo htmlspecialchars($row['imagine']); ?>" alt="Previzualizare" style="margin-top:10px; max-width: 100%;">
    </div>
</div>


        <div class="actions">
        <button type="submit">Save changes</button>
        </div>
       <div class="cancel">
        <a href="AdminDashboard.php">Cancel</a>
    </div>
    
    </form>
    </div>
    <script>
const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("imagine");
const preview = document.getElementById("preview");

dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("hover");
});

dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("hover");
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("hover");
    const file = e.dataTransfer.files[0];
    handleFile(file);
});

dropArea.addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    handleFile(file);
});

document.addEventListener("paste", (e) => {
    const items = e.clipboardData.items;
    for (let i = 0; i < items.length; i++) {
        if (items[i].type.indexOf("image") !== -1) {
            const file = items[i].getAsFile();
            handleFile(file);
            break;
        }
    }
});

function handleFile(file) {
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
        fileInput.files = createFileList(file);
    }
}

function createFileList(file) {
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    return dataTransfer.files;
}

document.getElementById('modificaProdusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('SalveazaModificari.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
        
        window.location.href = 'AdminDashboard.php';
    })
    .catch(error => {
        console.error('Eroare:', error);
        alert('Error');
    });
</script>

</body>
</html>
