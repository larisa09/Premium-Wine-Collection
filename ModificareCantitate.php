<?php
session_start();

if (!isset($_SESSION['cos']) || !isset($_SESSION['cantitati'])) {
    header("Location: AfisareProduseCos.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produs_id'], $_POST['actiune'])) {
        $produs_id = (int)$_POST['produs_id'];
        $actiune = $_POST['actiune'];

        if ($actiune === 'increase') {
            $_SESSION['cantitati'][$produs_id] = ($_SESSION['cantitati'][$produs_id] ?? 1) + 1;

            if (!in_array($produs_id, $_SESSION['cos'])) {
                $_SESSION['cos'][] = $produs_id;
            }

        } elseif ($actiune === 'decrease') {
            if (isset($_SESSION['cantitati'][$produs_id]) && $_SESSION['cantitati'][$produs_id] > 1) {
                $_SESSION['cantitati'][$produs_id]--;
            } else {
                unset($_SESSION['cantitati'][$produs_id]);
                $_SESSION['cos'] = array_diff($_SESSION['cos'], [$produs_id]);
            }
        }
    }
}

header("Location: AfisareProduseCos.php");
exit();
