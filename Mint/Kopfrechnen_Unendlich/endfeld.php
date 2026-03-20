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
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Spielende – Zeitmodus</title>
<style>
    body { margin:0; font-family:Arial,sans-serif; background:#ecc2f0; display:flex; justify-content:center; align-items:center; min-height:100vh; }
    .end-screen { background:#fff; padding:40px; border-radius:16px; text-align:center; width:360px; box-shadow:0 20px 40px rgba(0,0,0,0.2); }
    .end-screen h1 { margin-bottom:20px; font-size:28px; color:#333; }
    .stats p { font-size:18px; margin:10px 0; font-weight:bold; }
    .buttons { margin-top:20px; display:flex; flex-direction:column; gap:10px; }
    a.button-link { display:inline-block; text-decoration:none; width:100%; padding:12px; border-radius:10px; background:#800080; color:white; font-size:16px; transition:0.2s; box-sizing:border-box; }
    a.button-link:hover { background:#5e368c; }
    a.button-link.home { background:#457b9d; }
    a.button-link.home:hover { background:#2c5f7a; }
    .saved-note { font-size:13px; color:#888; margin-top:8px; }
</style>
</head>
<body>
<a href="../Hub_Mainpage/Hub_Lernseite_Mint.php" style="position:fixed;top:16px;left:16px;z-index:9999;display:inline-flex;align-items:center;gap:8px;background:rgba(20,30,55,0.85);border:1px solid rgba(255,255,255,0.15);backdrop-filter:blur(8px);color:#e5e7eb;text-decoration:none;padding:9px 18px;border-radius:999px;font-family:Arial,sans-serif;font-size:14px;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.4);" onmouseover="this.style.background='rgba(69,123,157,0.9)'" onmouseout="this.style.background='rgba(20,30,55,0.85)'">&#8592; Hub</a>
<div class="end-screen">
  <h1>Spielende</h1>
  <div class="stats"><p>Punkte: <?= $score ?></p></div>
  <?php if (!empty($_SESSION['user_id'])): ?>
    <p class="saved-note">✅ Punkte gespeichert!</p>
  <?php endif; ?>
  <div class="buttons">
    <a href="startfeld.html" class="button-link">Neu starten</a>
  </div>
</div>
</body>
</html>
