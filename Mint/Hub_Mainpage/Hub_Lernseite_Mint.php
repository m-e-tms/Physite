<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../db.php';

$isGuest = isset($_GET['guest']) || empty($_SESSION['user_id']);
$username = $_SESSION['username'] ?? null;

// Fetch top-5 leaderboard data
$db = getDB();

function getTop5(mysqli $db, string $col): array {
    $stmt = $db->prepare("SELECT username, `$col` as score FROM users WHERE `$col` > 0 ORDER BY `$col` DESC LIMIT 5");
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
}

$top_zeit   = getTop5($db, 'score_kopfrechnen_zeit');
$top_level  = getTop5($db, 'score_kopfrechnen_level');
$top_raetsel= getTop5($db, 'score_raetsel');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Hub</title>
    <link rel="stylesheet" href="../test.css">
    <link rel="stylesheet" href="Hub.css">
</head>
<body>
<canvas id="space-canvas"></canvas>
<main class="stage" style="position:relative;">

    <!-- Top-right user bar -->
    <div class="top-bar">
        <?php if ($username): ?>
            <span class="user-badge">👤 <?= htmlspecialchars($username) ?></span>
            <a class="logout-btn" href="../logout.php">Abmelden</a>
        <?php else: ?>
            <a class="logout-btn" href="../Anmeldeseite/Anmeldeseite.php">Anmelden</a>
        <?php endif; ?>
    </div>

    <h1>Hub</h1>
    <p><?= $username ? 'Willkommen zurück, <strong>' . htmlspecialchars($username) . '</strong>!' : 'Du spielst als Gast.' ?></p>

    <!-- Game cards -->
    <div class="games-grid">
        <a class="game-card" href="../Kopfrechnen_Unendlich/startfeld.html">
            <div class="icon">🧮</div>
            <h3>Kopfrechnen</h3>
            <p>Zeitmodus & Progressivmodus</p>
        </a>
        <a class="game-card" href="../Raetsel/quiz_Startseite.html">
            <div class="icon">🧩</div>
            <h3>Rätsel</h3>
            <p>Quizgeschichten lösen</p>
        </a>
        <a class="game-card" href="../Stochastik_Glücksspiel/startseite.html">
            <div class="icon">🎲</div>
            <h3>Stochastik</h3>
            <p>Würfel & Glücksrad</p>
        </a>
    </div>

    <!-- Leaderboard -->
    <div class="lb-section">
        <h2>🏆 Bestenliste – Top 5 je Spiel</h2>
        <div class="lb-grid">

            <!-- Kopfrechnen Zeitmodus -->
            <div class="lb-card">
                <h3>Kopfrechnen – Zeitmodus</h3>
                <?php if (empty($top_zeit)): ?>
                    <p class="lb-empty">Noch keine Einträge.</p>
                <?php else: ?>
                <table>
                    <tr><th>#</th><th>Name</th><th>Punkte</th></tr>
                    <?php foreach ($top_zeit as $i => $r): ?>
                    <tr>
                        <td class="rank"><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($r['username']) ?></td>
                        <td><?= (int)$r['score'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>

            <!-- Kopfrechnen Progressivmodus -->
            <div class="lb-card">
                <h3>Kopfrechnen – Progressivmodus</h3>
                <?php if (empty($top_level)): ?>
                    <p class="lb-empty">Noch keine Einträge.</p>
                <?php else: ?>
                <table>
                    <tr><th>#</th><th>Name</th><th>Punkte</th></tr>
                    <?php foreach ($top_level as $i => $r): ?>
                    <tr>
                        <td class="rank"><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($r['username']) ?></td>
                        <td><?= (int)$r['score'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>

            <!-- Rätsel -->
            <div class="lb-card">
                <h3>Rätsel</h3>
                <?php if (empty($top_raetsel)): ?>
                    <p class="lb-empty">Noch keine Einträge.</p>
                <?php else: ?>
                <table>
                    <tr><th>#</th><th>Name</th><th>Abgeschlossen</th></tr>
                    <?php foreach ($top_raetsel as $i => $r): ?>
                    <tr>
                        <td class="rank"><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($r['username']) ?></td>
                        <td><?= (int)$r['score'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>

        </div>
    </div>

</main>
<script src="../space.js"></script>
</body>
</html>
