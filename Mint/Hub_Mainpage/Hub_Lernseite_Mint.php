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
    <style>
        /* ---- Top bar ---- */
        .top-bar {
            position: absolute;
            top: 1.5rem;
            right: 2rem;
            display: flex;
            align-items: center;
            gap: 14px;
            z-index: 10;
        }
        .user-badge {
            background: rgba(96,165,250,0.15);
            border: 1px solid rgba(96,165,250,0.3);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 0.9rem;
            color: var(--accent);
            font-weight: 600;
        }
        .logout-btn {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: background .2s, color .2s;
        }
        .logout-btn:hover { background: rgba(239,68,68,.2); color: #fca5a5; }

        /* ---- Game cards ---- */
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .game-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
            transition: transform .3s, box-shadow .3s;
        }
        .game-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 0 0 1px rgba(96,165,250,.3), 0 25px 50px rgba(0,0,0,.5);
        }
        .game-card .icon { font-size: 2.5rem; margin-bottom: .75rem; }
        .game-card h3 { margin: 0; font-size: 1.1rem; color: var(--accent); }
        .game-card p  { font-size: .85rem; margin: .4rem 0 0; }

        /* ---- Leaderboard ---- */
        .lb-section { margin-top: 2.5rem; }
        .lb-section h2 { font-size: 1.2rem; margin-bottom: 1rem; color: var(--text-primary); }
        .lb-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .lb-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 18px;
            padding: 1.25rem 1.5rem;
        }
        .lb-card h3 {
            font-size: .95rem;
            color: var(--accent);
            margin: 0 0 .75rem;
            letter-spacing: .03em;
        }
        .lb-card table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        .lb-card th { color: var(--text-secondary); text-align: left; padding: 4px 6px; font-weight: 500; font-size: .8rem; }
        .lb-card td { padding: 5px 6px; color: var(--text-primary); border-top: 1px solid rgba(255,255,255,.05); }
        .lb-card .rank { color: var(--text-secondary); font-size: .8rem; }
        .lb-card tr:first-child td { color: #fcd34d; font-weight: 700; }
        .lb-card tr:nth-child(2) td { color: #d1d5db; }
        .lb-card tr:nth-child(3) td { color: #b45309; }
        .lb-empty { color: var(--text-secondary); font-size: .85rem; padding: 4px 6px; }

        /* ---- Home link in sub-games ---- */
        .home-hint { font-size: .85rem; color: var(--text-secondary); margin-top: 2rem; }
        .home-hint a { color: var(--accent); }
    </style>
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
