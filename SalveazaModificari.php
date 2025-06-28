<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: AdminDashboard.php");
    exit;
}

include 'Conexiune.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nume = $_POST['nume'];
    $descriere = $_POST['descriere'];
    $pret = floatval($_POST['pret']);

    $imagine_finala = ""; // aici se va decide imaginea

    if (isset($_FILES['imagine']) && $_FILES['imagine']['error'] === UPLOAD_ERR_OK) {
        $numeFisier = basename($_FILES['imagine']['name']);
        $tipFisier = strtolower(pathinfo($numeFisier, PATHINFO_EXTENSION));

        $tipuriPermise = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($tipFisier, $tipuriPermise)) {
            $destinatie = "imagini/" . uniqid() . "-" . $numeFisier;
            if (move_uploaded_file($_FILES['imagine']['tmp_name'], $destinatie)) {
                $imagine_finala = basename($destinatie);
            } else {
                echo "Eroare la mutarea fișierului încărcat.";
                exit;
            }
        } else {
            echo "The file type isn't accepted.";
            exit;
        }
    } else {
        // dacă nu s-a încărcat o imagine nouă, preluăm imaginea veche
        $sql_get = "SELECT imagine FROM produse WHERE id = ?";
        $stmt_get = $conn->prepare($sql_get);
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $result = $stmt_get->get_result();
        if ($row = $result->fetch_assoc()) {
            $imagine_finala = $row['imagine'];
        }
        $stmt_get->close();
    }

    // actualizare produs
    $sql = "UPDATE produse SET nume=?, descriere=?, pret=?, imagine=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $nume, $descriere, $pret, $imagine_finala, $id);
    if ($stmt->execute()) {
        header("Location: AdminDashboard.php?update=success");
        exit;
    } else {
        echo "Eroare la salvarea modificărilor: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
