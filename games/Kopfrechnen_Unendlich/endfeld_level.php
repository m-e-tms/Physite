<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../db.php';

$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$scoreAdj = $score - 1;
$level = intdiv($scoreAdj, 5) + 1;

if (!empty($_SESSION['user_id']) && $scoreAdj > 0) {
    $db   = getDB();
    $stmt = $db->prepare("UPDATE users SET score_kopfrechnen_level = GREATEST(score_kopfrechnen_level, ?) WHERE id = ?");
    $stmt->bind_param('ii', $scoreAdj, $_SESSION['user_id']);
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
<b><font color='#FF0000'><p align="center">Die Eingabe war falsch oder die Zeit ist abgelaufen.</p></p></font></b>

<?php
$score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
$mode = isset($_GET["mode"]) ? intval($_GET["mode"]) : 0;
$score = $score-1;
$level = intdiv(($score+$mode), 5) + 1;
echo "<p>Erreichtes Level: $level mit $score Punkten</p>";
?></div>


    <a href="startfeld.html">Neu starten</a>

</div>

</body>
</html>