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
<style>
  .btn-group {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    margin-top: 1.5rem;
    max-width: 280px;
  }

  .btn {
    display: inline-block;
    padding: 0.65rem 1.75rem;
    border-radius: 12px;
    border: 1px solid var(--border-subtle);
    background: rgba(255,255,255,0.06);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    cursor: pointer;
    text-decoration: none;
    transition:
      background 0.3s var(--ease-out),
      transform  0.3s var(--ease-out),
      box-shadow 0.3s var(--ease-out);
  }

  .btn:hover {
    background: rgba(96,165,250,0.15);
    box-shadow:
      0 0 0 1px rgba(96,165,250,0.35),
      0 8px 24px rgba(0,0,0,0.4);
    transform: translateY(-2px);
  }

  .btn-primary {
    background: var(--accent);
    color: #05070c;
    font-weight: 600;
    border: none;
  }

  .btn-primary:hover {
    background: #93c5fd;
    box-shadow:
      0 0 0 1px rgba(96,165,250,0.5),
      0 8px 24px rgba(96,165,250,0.25);
  }
</style>
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