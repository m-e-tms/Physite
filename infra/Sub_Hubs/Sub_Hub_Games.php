<?php
require_once __DIR__ . '/../db_backend/db.php';

function getTop5(mysqli $db, string $col): array {
    $stmt = $db->prepare("SELECT username, `$col` as score FROM users WHERE `$col` > 0 ORDER BY `$col` DESC LIMIT 5");
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}

$db = getDB();
$topZeit = getTop5($db, 'score_kopfrechnen_zeit');
$topLevel = getTop5($db, 'score_kopfrechnen_level');
$topRaetsel = getTop5($db, 'score_raetsel');

function renderBoard(string $title, array $rows, string $scoreLabel): void {
    echo '<div class="lb-card">';
    echo '<h3>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>';
    if (!$rows) {
        echo '<p class="lb-empty">Noch keine Einträge.</p>';
        echo '</div>';
        return;
    }
    echo '<table><thead><tr><th>#</th><th>Name</th><th>' . htmlspecialchars($scoreLabel, ENT_QUOTES, 'UTF-8') . '</th></tr></thead><tbody>';
    foreach ($rows as $i => $row) {
        echo '<tr><td class="rank">' . ($i + 1) . '</td><td>'
            . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8')
            . '</td><td>' . (int) $row['score'] . '</td></tr>';
    }
    echo '</tbody></table></div>';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spiele</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../hub.css?v=7">
</head>
<body class="sub-hub" data-cat="games">
  <header>
    <p class="eyebrow">Themen &amp; Stufen · Physik</p>
    <h1>Spiele</h1>
    <div class="divider"></div>
    <p class="lede">Übungs- und Quizspiele.</p>
  </header>
  <div class="grid compact">
    <a class="card" href="../../content/games/Kopfrechnen_Unendlich/startfeld.html">
      <div class="card-bg"></div><div class="card-orb"></div>
      <div class="card-body">
        <p class="card-title">Kopfrechnen</p>
        <span class="card-arrow">Öffnen →</span>
      </div>
    </a>
    <a class="card" href="../../content/games/raetsel/quiz_Startseite.html">
      <div class="card-bg"></div><div class="card-orb"></div>
      <div class="card-body">
        <p class="card-title">Rätsel</p>
        <span class="card-arrow">Öffnen →</span>
      </div>
    </a>
    <a class="card" href="../../content/games/Stochastik_Glücksspiel/startseite.html">
      <div class="card-bg"></div><div class="card-orb"></div>
      <div class="card-body">
        <p class="card-title">Stochastik</p>
        <span class="card-arrow">Öffnen →</span>
      </div>
    </a>
  </div>
  <section class="lb-section">
    <h2>Bestenliste – Top 5 je Spiel</h2>
    <div class="lb-grid">
      <?php renderBoard('Kopfrechnen – Zeitmodus', $topZeit, 'Punkte'); ?>
      <?php renderBoard('Kopfrechnen – Progressivmodus', $topLevel, 'Punkte'); ?>
      <?php renderBoard('Rätsel', $topRaetsel, 'Abgeschlossen'); ?>
    </div>
  </section>
  <p class="back-row">
    <a href="../Hub_Themen/Hub_Themen.php">← Themen</a>
    <span>·</span>
    <a href="../Hub_Stufen/Hub_Stufen.php">Stufen →</a>
  </p>
  <footer class="page-foot">Thomas Mann Schule · Physik</footer>
</body>
</html>
