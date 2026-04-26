<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: Hub_Mainpage/Hub_Lernseite_Mint.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Startseite</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="test.css">
<link rel="stylesheet" href="index.css">
</head>
<body>
  <canvas id="space-canvas"></canvas>
  <main class="stage">
    <h1>Willkommen</h1>
    <div class="btn-group">
      <a href="Anmeldeseite/Anmeldeseite.php" class="btn btn-primary">Anmelden</a>
      <a href="Registrierungsseite/Registrierungsseite.php" class="btn btn-primary">Registrieren</a>
      <a href="Hub_Mainpage/Hub_Lernseite_Mint.php?guest=1" class="btn btn-primary">Als Gast verwenden</a>
    </div>
  </main>
  <script src="space.js"></script>
</body>
</html>