<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION = [];
session_destroy();

// Redirect relative to this file's location — works regardless of folder name
header('Location: Anmeldeseite/Anmeldeseite.php');
exit;
?>
