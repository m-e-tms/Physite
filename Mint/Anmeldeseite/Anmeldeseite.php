<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../db.php';

// Already logged in
if (!empty($_SESSION['user_id'])) {
    header('Location: ../Hub_Mainpage/Hub_Lernseite_Mint.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $part1  = trim($_POST['name1']  ?? '');
    $part2  = trim($_POST['name2']  ?? '');
    $number = trim($_POST['number'] ?? '');
    $pass   = $_POST['password']    ?? '';

    if (!$part1 || !$part2 || !$number || !$pass) {
        $error = 'Bitte alle Felder ausfüllen.';
    } else {
        $username = $part1 . $part2 . $number;
        $db       = getDB();
        $stmt     = $db->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['password_hash'])) {
                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];
                header('Location: ../Hub_Mainpage/Hub_Lernseite_Mint.php');
                exit;
            } else {
                $error = 'Falsches Passwort.';
            }
        } else {
            $error = 'Kein Benutzer mit diesem Namen gefunden.';
        }
        $stmt->close();
    }
}

// Number list
$blacklist    = [14, 18, 67, 69, 88];
$extraNumbers = ['007', '161', '3,14159265359'];
$numbers = [];
for ($i = 1; $i <= 99; $i++) {
    if (!in_array($i, $blacklist)) $numbers[] = (string)$i;
}
foreach ($extraNumbers as $n) {
    if (!in_array($n, $blacklist)) $numbers[] = $n;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Anmeldeseite</title>
    <link rel="stylesheet" href="../test.css">
    <style>
        .form-group {
            margin: 14px 0;
        }
        label {
            display: block;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        select, input[type="password"] {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            color: var(--text-primary);
            padding: 10px 14px;
            font-size: 1rem;
            width: 100%;
            max-width: 340px;
            outline: none;
            transition: border-color .2s;
        }
        select:focus, input[type="password"]:focus {
            border-color: var(--accent);
        }
        select option { background: #1a2340; }
        .preview {
            margin: 18px 0 8px;
            font-size: 1.05rem;
            color: var(--text-secondary);
        }
        .preview strong { color: var(--accent); font-size: 1.2rem; }
        button#loginBtn {
            margin-top: 10px;
            padding: 12px 32px;
            background: var(--accent);
            color: #05070c;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s, transform .2s;
        }
        button#loginBtn:disabled {
            opacity: .35;
            cursor: not-allowed;
        }
        button#loginBtn:not(:disabled):hover {
            opacity: .85;
            transform: translateY(-2px);
        }
        .msg-error {
            background: rgba(239,68,68,.15);
            border: 1px solid rgba(239,68,68,.4);
            color: #fca5a5;
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
        }
        .back-link { margin-top: 20px; display: inline-block; color: var(--text-secondary); font-size: .9rem; }
        .back-link:hover { color: var(--accent); }
        .reg-hint { margin-top: 12px; font-size: .9rem; color: var(--text-secondary); }
        .reg-hint a { color: var(--accent); }
    </style>
</head>
<body>
<canvas id="space-canvas"></canvas>
<main class="stage">
    <h1>Anmelden</h1>

    <?php if ($error): ?>
        <div class="msg-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm">

        <p>Wähle deinen Benutzernamen:</p>

        <div class="form-group">
            <label>Teil 1</label>
            <select id="name1" name="name1" onchange="updatePreview()">
                <option value="">-- Bitte wählen --</option>
                <?php foreach (['Hippy','Bohne','Mayo','Delta','Epsilon','Zebra','Theta','Kopper','Lama','Harzer','Onion','Supernova','Cosmos','Astra','Solar','Lunar','TerraX','Neo','Cytra','Pixel'] as $o): ?>
                    <option <?= ($_POST['name1'] ?? '') === $o ? 'selected' : '' ?>><?= $o ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Teil 2</label>
            <select id="name2" name="name2" onchange="updatePreview()">
                <option value="">-- Bitte wählen --</option>
                <?php foreach (['Rider','Master','Coder','Walker','Runner','Dreamer','Käse','Wizard','Hero','Star','Beast','Bot','Engine','Machine','Pilot','Guardian','Ranger','Genius','Power','Vision'] as $o): ?>
                    <option <?= ($_POST['name2'] ?? '') === $o ? 'selected' : '' ?>><?= $o ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nummer</label>
            <select id="number" name="number" onchange="updatePreview()">
                <option value="">-- Nr. --</option>
                <?php foreach ($numbers as $n): ?>
                    <option value="<?= htmlspecialchars($n) ?>" <?= ($_POST['number'] ?? '') === $n ? 'selected' : '' ?>><?= htmlspecialchars($n) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="preview">Dein Name: <strong id="namePreview">–</strong></div>

        <div class="form-group">
            <label>Passwort</label>
            <input type="password" name="password" id="password" placeholder="Passwort" oninput="checkForm()">
        </div>

        <br>
        <button type="submit" id="loginBtn" disabled>Anmelden</button>
    </form>

    <p class="reg-hint">Noch kein Konto? <a href="../Registrierungsseite/Registrierungsseite.php">Jetzt registrieren</a></p>
    <a class="back-link" href="../index.php">← Zurück zur Startseite</a>
</main>

<script src="../space.js"></script>
<script>
function updatePreview() {
    const p1 = document.getElementById('name1').value;
    const p2 = document.getElementById('name2').value;
    const nr = document.getElementById('number').value;
    document.getElementById('namePreview').textContent = (p1 && p2 && nr) ? p1 + p2 + nr : '–';
    checkForm();
}
function checkForm() {
    const p1  = document.getElementById('name1').value;
    const p2  = document.getElementById('name2').value;
    const nr  = document.getElementById('number').value;
    const pw  = document.getElementById('password').value;
    const btn = document.getElementById('loginBtn');
    btn.disabled = !(p1 && p2 && nr && pw.length >= 1);
}
</script>
</body>
</html>
