<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produs_id']) && is_numeric($_POST['produs_id'])) {
        $produs_id = (int)$_POST['produs_id'];

        // Inițializează coșul dacă nu există
        if (!isset($_SESSION['cos'])) {
            $_SESSION['cos'] = array();
        }

        // Adaugă produsul în coș (dacă nu e deja)
        if (!in_array($produs_id, $_SESSION['cos'])) {
            $_SESSION['cos'][] = $produs_id;
        }

        // Inițializează vectorul de cantități dacă nu există
        if (!isset($_SESSION['cantitati'])) {
            $_SESSION['cantitati'] = [];
        }

        // Crește cantitatea
        if (isset($_SESSION['cantitati'][$produs_id])) {
            $_SESSION['cantitati'][$produs_id]++;
            echo "The product quantity has been increased!";
        } else {
            $_SESSION['cantitati'][$produs_id] = 1;
            echo "The product was added into the cart!";
        }

    } else {
        echo "Eroare: ID-ul produsului nu a fost trimis sau este invalid.";
    }
} else {
    echo "Cerere invalidă.";
}
?>
