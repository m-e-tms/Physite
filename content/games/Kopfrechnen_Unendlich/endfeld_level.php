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
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spiel beendet</title>
  <link rel="stylesheet" href="../../../resources/Vorlage_Standard/test.css">
  <link rel="stylesheet" href="kopfrechnen.css">
</head>
<body>

  <nav>
    <div class="element" onclick="location.href='../../../infra/Hub_Lernseite_Mint.php'">Home</div>
    <div class="element">Scoreboard</div>
    <div class="element">News</div>
    <div class="element no-border dropdown">
      Spiele
      <div class="dropdown-content">
        <button onclick="location.href='startfeld.html'">Kopfrechnen</button>
        <button onclick="location.href='../raetsel/quiz_Startseite.html'">Rätsel</button>
      </div>
    </div>
  </nav>

<div class="card">
<p class="mode-label">Progressivmodus</p>
<div class="widget">
<h1>Spiel beendet</h1>
<p class="status-bad">Die Eingabe war falsch oder die Zeit ist abgelaufen.</p>

<?php
$score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
$mode = isset($_GET["mode"]) ? intval($_GET["mode"]) : 0;
$score = $score-1;
$level = intdiv(($score+$mode), 5) + 1;
echo "<p>Erreichtes Level: $level mit $score Punkten</p>";
?></div>
    <div class="restart-row">
      <a href="startfeld.html" class="button-link">Neu starten</a>
    </div>
</div>

</body>
</html>