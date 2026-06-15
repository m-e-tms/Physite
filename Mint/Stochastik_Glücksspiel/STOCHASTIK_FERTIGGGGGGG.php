<?php
session_start();

// session-system
if (isset($_POST['spin'])) {
    $_SESSION['wheel_result'] = rand(1, 4);
    header('Location: ?step=2');
    exit;
}
if (isset($_POST['roll'])) {
    $_SESSION['dice_result'] = rand(1, 6);
    header('Location: ?step=3');
    exit;
}

// current step finden
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// result leeren
if ($step == 1) {
    unset($_SESSION['wheel_result']);
    unset($_SESSION['dice_result']);
}

// vars
$wheelResult = isset($_SESSION['wheel_result']) ? (int)$_SESSION['wheel_result'] : 0;
$diceResult  = isset($_SESSION['dice_result'])  ? (int)$_SESSION['dice_result']  : 0;

$farben  = array(1 => 'Rot', 2 => 'Grün', 3 => 'Blau', 4 => 'Gelb');
$hex     = array(1 => '#e02020', 2 => '#22c55e', 3 => '#3b82f6', 4 => '#eab308');
$centre  = array(1 => 45, 2 => 135, 3 => 225, 4 => 315);

$targetDeg   = $wheelResult > 0 ? $centre[$wheelResult] : 0;
$resultName  = $wheelResult > 0 ? $farben[$wheelResult] : '';
$resultColor = $wheelResult > 0 ? $hex[$wheelResult] : '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Stochastik Lernseite</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
    .container { display: flex; min-height: 100vh; }

    .sidebar { width: 200px; background-color: #0066cc; color: white; padding: 10px; }
    .sidebar button { display: block; width: 100%; margin-bottom: 5px; padding: 8px; background-color: #005fa3; color: white; border: none; cursor: pointer; text-align: left; }
    .sidebar button.active { background-color: #00b4d8; }

    .main { flex: 1; padding: 20px; }
    .content-box { background-color: white; padding: 15px; margin-bottom: 10px; border-left: 4px solid #00b4d8; }

    /* progress */
    .progress { display: flex; align-items: center; margin-bottom: 20px; }
    .progress-step { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .progress-circle { width: 36px; height: 36px; border-radius: 50%; background: #ccc; color: white; font-weight: bold; font-size: 16px; display: flex; align-items: center; justify-content: center; }
    .progress-circle.done { background: #22c55e; }
    .progress-circle.active { background: #0066cc; }
    .progress-label { font-size: 12px; color: #555; }
    .progress-line { flex: 1; height: 3px; background: #ccc; margin: 0 6px; margin-bottom: 16px; }
    .progress-line.done { background: #22c55e; }

    .game-section { background: white; border-left: 4px solid #00b4d8; padding: 20px; }
    .game-layout { display: flex; align-items: center; gap: 30px; justify-content: center; }
    .game-side { display: flex; flex-direction: column; align-items: center; }

    #wheel-wrapper { position: relative; width: 400px; height: 430px; }
    #arrow { position: absolute; left: 50%; top: 0; transform: translateX(-50%); width: 0; height: 0; border-left: 16px solid transparent; border-right: 16px solid transparent; border-top: 32px solid #111; }
    #wheelCanvas { position: absolute; top: 30px; left: 0; }

    .result-text { font-size: 28px; font-weight: bold; margin: 14px 0; min-height: 36px; text-align: center; }

    .btn { font-size: 22px; padding: 12px 36px; margin-top: 16px; color: white; border: none; border-radius: 8px; cursor: pointer; }
    .btn:disabled { background-color: #aaa; cursor: not-allowed; }
    .btn-green { background-color: #66e86a; }
    .btn-green:hover:not(:disabled) { background-color: #44cc50; }
    .btn-blue { background-color: #2196F3; }
    .btn-blue:hover:not(:disabled) { background-color: #1976D2; }
    .btn-next { background-color: #0066cc; font-size: 18px; padding: 10px 28px; margin-top: 20px; }
    .btn-next:hover { background-color: #005fa3; }


  </style>
</head>
<!-- body start, buttons zum sidebar-->
<body>
<div class="container">
  <div class="sidebar">
    <h3>Themen</h3>
    <button onclick="showSection('wahrscheinlichkeit', this)">Wahrscheinlichkeit</button>
    <button onclick="showSection('zufallsversuche', this)">Zufallsversuche</button>
    <button onclick="showSection('baumdiagramme', this)">Baumdiagramme</button>
  </div>

  <div class="main">
    <h1 style="color:#0066cc; text-align:center">STOCHASTIK</h1>

    <!-- sidebar inhalt -->
    <div id="wahrscheinlichkeit" class="content-box" style="display:none">
      <h2>Wahrscheinlichkeit</h2>
      <p>Wahrscheinlichkeit sagt dir, wie wahrscheinlich etwas passiert. 🎲</p>
      <ul>
        <li>Beispiel: Die Chance, eine 6 zu würfeln, ist 1 von 6.</li>
        <li>Wenn du 6-mal würfelst, erscheint die 6 ungefähr einmal.</li>
        <li>Mit der Wahrscheinlichkeit kannst du vorhersagen, wie oft ein Ergebnis auftreten könnte.</li>
      </ul>
    </div>
    <div id="zufallsversuche" class="content-box" style="display:none">
      <h2>Zufallsversuche</h2>
      <p>Ein Zufallsversuch ist ein Experiment, bei dem du vorher nicht weißt, was passiert. 🃏</p>
      <p>Typische Beispiele: Würfeln, Glücksrad drehen, Karte ziehen.</p>
      <ul>
        <li>Jeder Spielzug ist ein Zufallsversuch.</li>
        <li>Du kannst beobachten, welche Ergebnisse häufiger oder seltener vorkommen.</li>
      </ul>
    </div>
    <div id="baumdiagramme" class="content-box" style="display:none">
      <h2>Baumdiagramme</h2>
      <p>Ein Baumdiagramm zeigt alle möglichen Ergebnisse eines Zufallsexperiments. 🌳</p>
      <ul>
        <li>Welche Farben oder Zahlen es gibt</li>
        <li>Welche Reihenfolgen auftreten könnten</li>
        <li>Wie wahrscheinlich jede Möglichkeit ist</li>
      </ul>
    </div>

    <!-- Prog bar oben konstant -->
    <div class="progress">
      <div class="progress-step">
        <div class="progress-circle <?= $step >= 1 ? ($step > 1 ? 'done' : 'active') : '' ?>">1</div>
        <div class="progress-label">Start</div>
      </div>
      <div class="progress-line <?= $step > 1 ? 'done' : '' ?>"></div>
      <div class="progress-step">
        <div class="progress-circle <?= $step >= 2 ? ($step > 2 ? 'done' : 'active') : '' ?>">2</div>
        <div class="progress-label">Glücksrad</div>
      </div>
      <div class="progress-line <?= $step > 2 ? 'done' : '' ?>"></div>
      <div class="progress-step">
        <div class="progress-circle <?= $step >= 3 ? ($step > 3 ? 'done' : 'active') : '' ?>">3</div>
        <div class="progress-label">Würfelspiel</div>
      </div>
      <div class="progress-line <?= $step > 3 ? 'done' : '' ?>"></div>
      <div class="progress-step">
        <div class="progress-circle <?= $step >= 4 ? 'active' : '' ?>">4</div>
        <div class="progress-label">Auswertung</div>
      </div>
    </div>

    <!-- step 1: Intro -->
    <?php if ($step == 1): ?>
    <div class="content-box">
      <h2>Einführung in die Stochastik</h2>
      <p>Willkommen in der spannenden Welt des Zufalls!</p>
      <p>Hier kannst du herausfinden, wie Wahrscheinlichkeiten funktionieren, warum manche Ergebnisse überraschend sind und wie man das alles spielerisch entdecken kann.</p>
      <p>In dieser Übung wirst du zuerst ein <strong>Glücksrad</strong> drehen und danach einen <strong>Würfel</strong> werfen. Am Ende schauen wir gemeinsam, was passiert ist.</p>
      <p>Beide Versuche sind <strong>Laplace-Experimente</strong> – jedes mögliche Ergebnis ist gleich wahrscheinlich.</p>
      <p>Bist du bereit?</p>
      <a href="?step=2"><button class="btn btn-green" style="margin-top:10px">Los geht's! 🎡</button></a>
    </div>

    <!-- step 2: Drehrad - Baumdiagramm -->
    <?php elseif ($step == 2): ?>
    <div class="game-section">
      <h2 style="text-align:center">Schritt 1: Glücksrad</h2>
      <p style="text-align:center">Dreh das Rad! Jede Farbe hat eine Chance von <strong>25 % (1/4)</strong>.</p>

      <div class="game-layout">
        <div>
          <svg width="220" height="380">
            <circle cx="40" cy="190" r="14" fill="#0066cc"/>
            <text x="40" y="195" text-anchor="middle" fill="white" font-size="11" font-weight="bold">Start</text>

            <line id="line-1" x1="54" y1="183" x2="152" y2="62"  stroke="#aaa" stroke-width="2"/>
            <text x="90"  y="108" text-anchor="middle" fill="#555" font-size="12">1/4</text>
            <line id="line-2" x1="54" y1="187" x2="152" y2="132" stroke="#aaa" stroke-width="2"/>
            <text x="103" y="152" text-anchor="middle" fill="#555" font-size="12">1/4</text>
            <line id="line-3" x1="54" y1="193" x2="152" y2="248" stroke="#aaa" stroke-width="2"/>
            <text x="103" y="228" text-anchor="middle" fill="#555" font-size="12">1/4</text>
            <line id="line-4" x1="54" y1="197" x2="152" y2="318" stroke="#aaa" stroke-width="2"/>
            <text x="90"  y="272" text-anchor="middle" fill="#555" font-size="12">1/4</text>

            <circle cx="170" cy="60"  r="18" fill="#e02020"/>
            <text x="170" y="65"  text-anchor="middle" fill="white" font-size="11" font-weight="bold">Rot</text>
            <circle cx="170" cy="130" r="18" fill="#22c55e"/>
            <text x="170" y="135" text-anchor="middle" fill="white" font-size="11" font-weight="bold">Grün</text>
            <circle cx="170" cy="250" r="18" fill="#3b82f6"/>
            <text x="170" y="255" text-anchor="middle" fill="white" font-size="11" font-weight="bold">Blau</text>
            <circle cx="170" cy="320" r="18" fill="#eab308"/>
            <text x="170" y="325" text-anchor="middle" fill="#333"  font-size="11" font-weight="bold">Gelb</text>
          </svg>
        </div>

        <div class="game-side">
          <div id="wheel-wrapper">
            <div id="arrow"></div>
            <canvas id="wheelCanvas" width="400" height="400"></canvas>
          </div>
          <div id="result-text" class="result-text"></div>
          <form method="POST">
            <button type="submit" name="spin" id="spin-btn" class="btn btn-green"><?= $wheelResult > 0 ? 'Nochmal drehen!' : 'Drehen!' ?></button>
          </form>
          <div id="next-btn" style="display:none">
            <a href="?step=3"><button class="btn btn-next">Weiter zum Würfelspiel</button></a>
          </div>
        </div>
      </div>
    </div>

    <!-- step 3: Würfelspiel - Baumdiagramm -->
    <?php elseif ($step == 3): ?>
    <div class="game-section">
      <h2 style="text-align:center">Schritt 2: Würfelspiel</h2>
      <p style="text-align:center">Wirf den Würfel! Jede Zahl hat eine Chance von <strong>16,67 % (1/6)</strong>.</p>

      <div class="game-layout">
        <div>
          <svg width="220" height="560">
            <circle cx="40" cy="280" r="14" fill="#0066cc"/>
            <text x="40" y="285" text-anchor="middle" fill="white" font-size="11" font-weight="bold">Start</text>

            <line id="dline-1" x1="54" y1="272" x2="152" y2="50"  stroke="#aaa" stroke-width="2"/>
            <text x="85"  y="140" text-anchor="middle" fill="#555" font-size="12">1/6</text>
            <line id="dline-2" x1="54" y1="276" x2="152" y2="140" stroke="#aaa" stroke-width="2"/>
            <text x="100" y="196" text-anchor="middle" fill="#555" font-size="12">1/6</text>
            <line id="dline-3" x1="54" y1="279" x2="152" y2="220" stroke="#aaa" stroke-width="2"/>
            <text x="108" y="242" text-anchor="middle" fill="#555" font-size="12">1/6</text>
            <line id="dline-4" x1="54" y1="281" x2="152" y2="310" stroke="#aaa" stroke-width="2"/>
            <text x="108" y="305" text-anchor="middle" fill="#555" font-size="12">1/6</text>
            <line id="dline-5" x1="54" y1="284" x2="152" y2="400" stroke="#aaa" stroke-width="2"/>
            <text x="100" y="372" text-anchor="middle" fill="#555" font-size="12">1/6</text>
            <line id="dline-6" x1="54" y1="288" x2="152" y2="490" stroke="#aaa" stroke-width="2"/>
            <text x="85"  y="418" text-anchor="middle" fill="#555" font-size="12">1/6</text>

            <circle cx="170" cy="50"  r="18" fill="#555"/>
            <text x="170" y="55"  text-anchor="middle" fill="white" font-size="13" font-weight="bold">1</text>
            <circle cx="170" cy="140" r="18" fill="#555"/>
            <text x="170" y="145" text-anchor="middle" fill="white" font-size="13" font-weight="bold">2</text>
            <circle cx="170" cy="220" r="18" fill="#555"/>
            <text x="170" y="225" text-anchor="middle" fill="white" font-size="13" font-weight="bold">3</text>
            <circle cx="170" cy="310" r="18" fill="#555"/>
            <text x="170" y="315" text-anchor="middle" fill="white" font-size="13" font-weight="bold">4</text>
            <circle cx="170" cy="400" r="18" fill="#555"/>
            <text x="170" y="405" text-anchor="middle" fill="white" font-size="13" font-weight="bold">5</text>
            <circle cx="170" cy="490" r="18" fill="#555"/>
            <text x="170" y="495" text-anchor="middle" fill="white" font-size="13" font-weight="bold">6</text>
          </svg>
        </div>

        <div class="game-side">
          <canvas id="diceCanvas" width="300" height="300"></canvas>
          <div id="dice-result-text" class="result-text"></div>
          <form method="POST">
            <button type="submit" name="roll" id="roll-btn" class="btn btn-blue"><?= $diceResult > 0 ? 'Nochmal würfeln!' : 'Würfeln!' ?></button>
          </form>
          <div id="next-btn" style="display:none">
            <a href="?step=4"><button class="btn btn-next">Weiter zur Auswertung</button></a>
          </div>
        </div>
      </div>
    </div>

    <!-- step 4: auswertung -->
    <?php elseif ($step == 4): ?>
    <div class="content-box">
      <h2>Auswertung</h2>
      <p>Du hast beide Zufallsversuche abgeschlossen. Hier ist, was passiert ist:</p>

      <div class="content-box" style="margin-top:15px">
        <p>In den Experimenten war zu erkennen, dass der Würfel geringere Wahrscheinlichkeiten für das jeweilige Ergebnis hatte, aber dafür 6 statt 4 verschiedene Ergebnisse zu erzielen waren.</p>
        <p>Obwohl der Würfel mehr verschiedene Ergebnisse hat als das Glücksrad, ist bei beiden Experimenten die jeweilige Chance immer der Anzahl der möglichen Ergebnisse entsprechend <strong>(= Laplace Experiment)!</strong></p>
      </div>

      <a href="?step=1"><button class="btn btn-green" style="margin-top:10px">Nochmal von vorne 🔄</button></a>
    </div>
    <?php endif; ?>

  </div>
</div>

<script>
var infoSections = ['wahrscheinlichkeit', 'zufallsversuche', 'baumdiagramme'];

// fnc sektion zeigen je ausgewahlt
function showSection(id, btn) {
    for (var i = 0; i < infoSections.length; i++) {
        document.getElementById(infoSections[i]).style.display = 'none';
    }
    document.getElementById(id).style.display = 'block';
    var btns = document.querySelectorAll('.sidebar button');
    for (var i = 0; i < btns.length; i++) btns[i].classList.remove('active');
    btn.classList.add('active');
}

// nachster step
<?php if ($step == 2): ?>
var wctx = document.getElementById('wheelCanvas').getContext('2d');
var colors = ['#e02020', '#22c55e', '#3b82f6', '#eab308'];

// rad zeichen
function drawWheel(deg) {
    wctx.clearRect(0, 0, 400, 400);
    var rot = deg * Math.PI / 180;
    for (var i = 0; i < 4; i++) {
        wctx.beginPath();
        wctx.moveTo(200, 200);
        wctx.arc(200, 200, 150, rot + i * Math.PI / 2, rot + (i + 1) * Math.PI / 2);
        wctx.closePath();
        wctx.fillStyle = colors[i];
        wctx.fill();
        wctx.strokeStyle = '#fff';
        wctx.lineWidth = 2;
        wctx.stroke();
    }
    wctx.beginPath(); wctx.arc(200, 200, 150, 0, Math.PI * 2); wctx.strokeStyle = '#222'; wctx.lineWidth = 3; wctx.stroke();
    wctx.beginPath(); wctx.arc(200, 200, 10, 0, Math.PI * 2); wctx.fillStyle = '#111'; wctx.fill();
}

// vars verlegen ziel degree, resultate, etc
var targetDeg = <?= (int)$targetDeg ?>;
var resultName = "<?= addslashes($resultName) ?>";
var resultColor = "<?= addslashes($resultColor) ?>";
var hasResult = <?= $wheelResult > 0 ? 'true' : 'false' ?>;
var finalRot = hasResult ? ((270 - targetDeg + 3600) % 360) + 6 * 360 : 0;
var animStart = null;

// mathematische funktion rotation des rades
function animate(ts) {
    if (!animStart) animStart = ts;
    var t = Math.min((ts - animStart) / 3000, 1);
    drawWheel((1 - Math.pow(1 - t, 3)) * finalRot);
    if (t < 1) {
        requestAnimationFrame(animate);
    } else {
        drawWheel(finalRot % 360);
        document.getElementById('result-text').textContent = 'Ergebnis: ' + resultName;
        document.getElementById('result-text').style.color = resultColor;
        document.getElementById('spin-btn').disabled = false;
        document.getElementById('line-<?= (int)$wheelResult ?>').setAttribute('stroke', '#22c55e');
        document.getElementById('line-<?= (int)$wheelResult ?>').setAttribute('stroke-width', '4');
        document.getElementById('next-btn').style.display = 'block';
    }
}

// result prufen, animation beginnen
if (hasResult) {
    document.getElementById('spin-btn').disabled = true;
    requestAnimationFrame(animate);
} else {
    drawWheel(0);
}
<?php endif; ?>

<?php if ($step == 3): ?>
// Wurfel animation
var dctx = document.getElementById('diceCanvas').getContext('2d');

function drawDice(number) {
    dctx.clearRect(0, 0, 300, 300);
    dctx.fillStyle = 'white';
    dctx.fillRect(50, 50, 200, 200);
    dctx.strokeStyle = '#222';
    dctx.lineWidth = 4;
    dctx.strokeRect(50, 50, 200, 200);

    var dots = { c:[150,150], tl:[100,100], tr:[200,100], ml:[100,150], mr:[200,150], bl:[100,200], br:[200,200] };
    var faces = [[], ['c'], ['tl','br'], ['tl','c','br'], ['tl','tr','bl','br'], ['tl','tr','c','bl','br'], ['tl','tr','ml','mr','bl','br']];

    dctx.fillStyle = '#e02020';
    var face = faces[number] || [];
    for (var i = 0; i < face.length; i++) {
        var d = dots[face[i]];
        dctx.beginPath();
        dctx.arc(d[0], d[1], 15, 0, Math.PI * 2);
        dctx.fill();
    }
}

var diceResult = <?= $diceResult ?>;
// dice resultate
if (diceResult > 0) {
    drawDice(diceResult);
    document.getElementById('dice-result-text').textContent = 'Ergebnis: ' + diceResult;
    document.getElementById('dline-' + diceResult).setAttribute('stroke', '#22c55e');
    document.getElementById('dline-' + diceResult).setAttribute('stroke-width', '4');
    document.getElementById('next-btn').style.display = 'block';
} else {
    drawDice(0);
}
<?php endif; ?>
</script>
</body>
</html>
