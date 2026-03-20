<?php
// auth_check.php – auf geschützten Seiten einbinden
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../Anmeldeseite/Anmeldeseite.php');
    exit;
}
?>
