<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $part1   = trim($_POST['name1']   ?? '');
    $part2   = trim($_POST['name2']   ?? '');
    $number  = trim($_POST['number']  ?? '');
    $pass    = $_POST['password']     ?? '';
    $pass2   = $_POST['password2']    ?? '';

    if (!$part1 || !$part2 || !$number || !$pass) {
        $error = 'Bitte alle Felder ausfüllen.';
    } elseif ($pass !== $pass2) {
        $error = 'Die Passwörter stimmen nicht überein.';
    } elseif (strlen($pass) < 6) {
        $error = 'Das Passwort muss mindestens 6 Zeichen lang sein.';
    } else {
        $username = $part1 . $part2 . $number;
        $hash     = password_hash($pass, PASSWORD_BCRYPT);
        $db       = getDB();

        $stmt = $db->prepare("INSERT INTO users (name_part1, name_part2, name_number, username, password_hash) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss', $part1, $part2, $number, $username, $hash);

        if ($stmt->execute()) {
            $_SESSION['user_id']  = $db->insert_id;
            $_SESSION['username'] = $username;
            header('Location: ../Hub_Mainpage/Hub_Lernseite_Mint.php');
            exit;
        } else {
            if ($db->errno === 1062) {
                $error = 'Dieser Benutzername existiert bereits. Wähle eine andere Kombination.';
            } else {
                $error = 'Registrierung fehlgeschlagen. Bitte versuche es erneut.';
            }
        }
        $stmt->close();
    }
}

// ---- Zahlen-Liste (wie Original) ----
$blacklist    = [14, 18, 67, 69, 88];
$extraNumbers = ['007', '161', '3,14159265359'];
$numbers      = [];
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
    <title>Registrierungsseite</title>
    <link rel="stylesheet" href="../test.css">
    <link rel="stylesheet" href="Registrierung.css">
</head>
<body>
<canvas id="space-canvas"></canvas>
<main class="stage">
    <h1>Registrieren</h1>

    <?php if ($error): ?>
        <div class="msg-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="regForm">

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
            <label>Passwort (mindestens 6 Zeichen)</label>
            <input type="password" name="password" id="password" placeholder="Passwort" oninput="checkForm()">
        </div>
        <div class="form-group">
            <label>Passwort wiederholen</label>
            <input type="password" name="password2" id="password2" placeholder="Passwort bestätigen" oninput="checkForm()">
        </div>

        <br>
        <button type="submit" id="createBtn" disabled>Registrieren</button>
    </form>

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
    const p1   = document.getElementById('name1').value;
    const p2   = document.getElementById('name2').value;
    const nr   = document.getElementById('number').value;
    const pw   = document.getElementById('password').value;
    const pw2  = document.getElementById('password2').value;
    const btn  = document.getElementById('createBtn');
    btn.disabled = !(p1 && p2 && nr && pw.length >= 6 && pw === pw2);
}
</script>
</body>
</html>
