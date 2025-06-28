<?php
session_start();
include 'Conexiune.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['cantitati']) || !is_array($_SESSION['cantitati'])) {
        echo json_encode([
            "success" => false,
            "error" => "Coșul de cumpărături este gol."
        ]);
        exit;
    }

    $nume = $_POST['nume'] ?? '';
    $prenume = $_POST['prenume'] ?? '';
    $email = $_POST['email'] ?? '';
    $adresa = $_POST['adresa'] ?? '';
    $oras = $_POST['oras'] ?? '';
    $judet = $_POST['judet'] ?? '';
    $cod_postal = (string) ($_POST['cod_postal'] ?? '');
    $metoda_livrare = $_POST['livrare'] ?? '';
    $metoda_plata = $_POST['plata'] ?? '';
    $utilizator_id = $_SESSION['utilizator_id'] ?? 1;
    $data_comanda = date("Y-m-d");


    $lista_produse = [];
    foreach ($_SESSION['cantitati'] as $id_produs => $cantitate) {
        $lista_produse[] = "ID:$id_produs x$cantitate";
    }
    $comanda_text = implode(", ", $lista_produse);

    $nr_comanda = rand(100000, 999999);

    $sqlComanda = "INSERT INTO comenzi (utilizator_id, nr_comanda, status, data_comanda) VALUES (?, ?, 'plasata', ?)";
    $stmtComanda = $conn->prepare($sqlComanda);

    if ($stmtComanda) {
        $stmtComanda->bind_param("iis", $utilizator_id, $nr_comanda, $data_comanda);
        if ($stmtComanda->execute()) {
            $sqlDetalii = "INSERT INTO detalii_comenzi 
                (utilizator_id, nr_comanda, data_comanda, nume, prenume, email, adresa, oras, judet, cod_postal, metoda_livrare, metoda_plata)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtDetalii = $conn->prepare($sqlDetalii);
            if ($stmtDetalii) {
                $stmtDetalii->bind_param(
                    "iissssssssss",
                    $utilizator_id, $nr_comanda, $data_comanda,
                    $nume, $prenume, $email, $adresa,
                    $oras, $judet, $cod_postal,
                    $metoda_livrare, $metoda_plata
                );

                if ($stmtDetalii->execute()) {

  
    $sqlProdus = "INSERT INTO comenzi_produse (utilizator_id, nr_comanda, id_produs, cantitate, pret) VALUES (?, ?, ?, ?, ?)";
    $stmtProdus = $conn->prepare($sqlProdus);

    if (!$stmtProdus) {
        echo json_encode([
            "success" => false,
            "error" => "Eroare pregătire inserare produse: " . $conn->error
        ]);
        exit;
    }

    foreach ($_SESSION['cantitati'] as $id_produs => $cantitate) {
        
        $sqlPret = "SELECT pret FROM produse WHERE id = ?";
        $stmtPret = $conn->prepare($sqlPret);
        $stmtPret->bind_param("i", $id_produs);
        $stmtPret->execute();
        $resultPret = $stmtPret->get_result();
        $rowPret = $resultPret->fetch_assoc();
        $pret = $rowPret['pret'] ?? 0;
        $stmtPret->close();

        $stmtProdus->bind_param("iiiid", $utilizator_id, $nr_comanda, $id_produs, $cantitate, $pret);
        $stmtProdus->execute();
    }

    $stmtProdus->close();

    
    unset($_SESSION['cantitati']);

    echo json_encode([
        "success" => true,
        "mesaj" => "Comanda a fost plasată cu succes!",
        "nr_comanda" => $nr_comanda
    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "error" => "Eroare la salvarea detaliilor: " . $stmtDetalii->error
                    ]);
                }
                $stmtDetalii->close();
            } else {
                echo json_encode([
                    "success" => false,
                    "error" => "Eroare la pregătirea detaliilor: " . $conn->error
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Eroare la inserarea în comenzi: " . $stmtComanda->error
            ]);
        }
        $stmtComanda->close();
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Eroare pregătire inserare comenzi: " . $conn->error
        ]);
    }

    $conn->close();
} else {
    echo json_encode([
        "success" => false,
        "error" => "Cerere invalidă."
    ]);
}
?>
