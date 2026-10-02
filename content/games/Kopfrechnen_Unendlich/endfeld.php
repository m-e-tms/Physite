<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../../infra/db_backend/db.php';

$score = isset($_GET['score']) ? intval($_GET['score']) : 0;

// Save score if logged in and better than current best
if (!empty($_SESSION['user_id']) && $score > 0) {
    $db   = getDB();
    $stmt = $db->prepare("UPDATE users SET score_kopfrechnen_zeit = GREATEST(score_kopfrechnen_zeit, ?) WHERE id = ?");
    $stmt->bind_param('ii', $score, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spiel beendet</title>
  <link rel="stylesheet" href="../../../resources/Vorlage_Standard/test.css">
  <link rel="stylesheet" href="kopfrechnen.css">
<link rel="stylesheet" href="../../../resources/nav/physite-nav.css" data-physite-nav-css>
<script src="../../../resources/nav/physite-nav.js" defer data-physite-nav></script>
</head>
<body>

  <nav class="physite-nav" aria-label="Physite"></nav>

<div class="card">
<p class="mode-label">Zeitmodus</p>
<div class="widget">
  <h1>Spiel beendet</h1>
    <?php
    //session_start();
    $score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
    echo "<p>Punkte: $score</p>";
    ?>
  </div>
  <div class="restart-row">
    <a href="startfeld.html" class="button-link">Neu starten</a>
  </div>
</div>
</body>
</html>