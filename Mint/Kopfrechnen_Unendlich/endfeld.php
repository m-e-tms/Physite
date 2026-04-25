<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../db.php';

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
<html>
    <title>Aufgabe</title>
<head>

  <meta charset="UTF-8">
  <link rel="stylesheet" href="test.css">

</head>
<body>

  <nav>
    <div class="element">Home</div>
    <div class="element">Scoreboard</div>
    <div class="element">News</div>
    <div class="element no-border dropdown">
      Dropdown
      <div class="dropdown-content">
        <button>Link 1</button>
        <button>Link 2</button>
        <button>Link 3</button>
      </div>
    </div>
  </nav>

<div class="card"><p>Progressivmodus:</p>
<div class="widget">
  <h1>Spiel beendet...</h1>
    <?php
    //session_start();
    $score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
    echo "<p>Punkte: $score</p>";
    ?>
  </div> <a href="startfeld.html">Neu starten</a>
</div>
</body>
</html>