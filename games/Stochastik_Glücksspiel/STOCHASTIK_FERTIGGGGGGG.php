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
  <link rel="stylesheet" href="../../resources/test.css">
  <style>
    /*
      Page-specific rules on top of resources/test.css.
      Grey page/stage background; purple accent theme on panels and controls.
    */
    body {
      background-color: #222222;
      background-image: repeating-radial-gradient(circle at -100px -100px, #000000, #222222 150px, #000000 300px);
      background-attachment: fixed;
      font-family: Arial, sans-serif;
    }
    h1, h2, h3 { font-family: Arial, sans-serif; }
    p { color: #dddddd; }

    .page-title {
      color: #e0a3ff;
      text-align: center;
      margin-bottom: 1.75rem;
    }

    .stage {
      background: #111111 repeating-radial-gradient(circle at -100px -100px, #000000, #222222 150px, #000000 300px);
      border-color: #666666;
    }

    .stochastik-layout {
      display: flex;
      gap: 2rem;
      align-items: flex-start;
    }

    .topic-sidebar {
      width: 220px;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .topic-sidebar h3 {
      margin: 0 0 0.5rem;
      color: #cccccc;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }
    .topic-sidebar button {
      display: block;
      width: 100%;
      text-align: left;
      padding: 0.65rem 1rem;
      border-radius: 8px;
      border: 1px solid #666666;
      background-color: #222233;
      color: #ffffff;
      font-family: Arial, sans-serif;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    .topic-sidebar button:hover,
    .topic-sidebar button.active {
      background-image: linear-gradient(to bottom right, #440044, #000000, #000044);
      border-color: #999999;
    }

    .content-area {
      flex: 1;
      min-width: 0;
      text-align: left;
    }

    /* static panels, matching the .widget look from the shared game template */
    .info-box,
    .step-panel {
      display: block;
      background-color: #222233;
      background-image: linear-gradient(to bottom right, #440044, #000000, #000044);
      border: 1px solid #666666;
      border-radius: 16px;
      padding: 1.5rem 1.75rem;
      margin-bottom: 1.5rem;
      text-align: left;
    }
    .info-box h2,
    .step-panel h2 {
      color: #e0a3ff;
      margin-top: 0;
    }
    .info-box ul {
      padding-left: 1.2rem;
    }
    .info-box li {
      margin-bottom: 0.4rem;
    }

    .highlight-box {
      background-color: rgba(0, 0, 0, 0.25);
      border: 1px solid #666666;
      border-radius: 12px;
      padding: 1.1rem 1.4rem;
      margin-top: 1rem;
    }

    /* progress bar */
    .progress {
      display: flex;
      align-items: center;
      margin-bottom: 2rem;
    }
    .progress-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }
    .progress-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: #444444;
      color: #ffffff;
      font-weight: bold;
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }
    .progress-circle.active {
      background-image: linear-gradient(to bottom right, #440044, #000000, #000044);
    }
    .progress-circle.done {
      background-color: #228822;
    }
    .progress-label {
      font-size: 12px;
      color: #cccccc;
    }
    .progress-line {
      flex: 1;
      height: 3px;
      background-color: #444444;
      margin: 0 6px;
      margin-bottom: 22px;
      transition: background 0.2s;
    }
    .progress-line.done {
      background-color: #228822;
    }

    /* game section */
    .game-layout {
      display: flex;
      align-items: center;
      gap: 2.5rem;
      justify-content: center;
      flex-wrap: wrap;
    }
    .game-side {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #wheel-wrapper {
      position: relative;
      width: 400px;
      height: 430px;
    }
    #arrow {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 16px solid transparent;
      border-right: 16px solid transparent;
      border-top: 32px solid #ffffff;
    }
    #wheelCanvas {
      position: absolute;
      top: 30px;
      left: 0;
    }

    .result-text {
      font-size: 28px;
      font-weight: bold;
      margin: 14px 0;
      min-height: 36px;
      text-align: center;
      color: #ffffff;
    }

    /* buttons */
    .btn {
      font-size: 1.05rem;
      padding: 0.7rem 2rem;
      margin-top: 1rem;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-family: Arial, sans-serif;
      font-weight: bold;
      color: #ffffff;
      transition: transform 0.2s, background 0.2s;
    }
    .btn:disabled {
      background-color: #666666 !important;
      background-image: none !important;
      cursor: not-allowed;
      transform: none !important;
    }
    .btn:hover:not(:disabled) {
      transform: translateY(-2px);
    }

    .btn-spin {
      background-color: #66e86a;
      color: #111111;
    }
    .btn-spin:hover:not(:disabled) {
      background-color: #44cc50;
    }

    .btn-roll {
      background-color: #2196F3;
    }
    .btn-roll:hover:not(:disabled) {
      background-color: #1976D2;
    }

    .btn-next {
      background-image: linear-gradient(to bottom right, #440044, #000000, #000044);
      border: 1px solid #666666;
      font-size: 0.95rem;
      padding: 0.6rem 1.6rem;
    }
    .btn-next:hover {
      background-image: linear-gradient(to bottom right, #660066, #111111, #000066);
    }

    @media (max-width: 900px) {
      .stochastik-layout {
        flex-direction: column;
      }
      .topic-sidebar {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
      }
    }
  </style>
</head>
<!-- body start, buttons zum sidebar-->
<body>

<main class="stage">
  <h1 class="page-title">STOCHASTIK</h1>

  <div class="stochastik-layout">
    <div class="topic-sidebar">
      <h3>Themen</h3>
      <button onclick="showSection('wahrscheinlichkeit', this)">Wahrscheinlichkeit</button>
      <button onclick="showSection('zufallsversuche', this)">Zufallsversuche</button>
      <button onclick="showSection('baumdiagramme', this)">Baumdiagramme</button>
    </div>

    <div class="content-area">

      <!-- sidebar inhalt -->
      <div id="wahrscheinlichkeit" class="info-box" style="display:none">
        <h2>Wahrscheinlichkeit</h2>
        <p>Wahrscheinlichkeit beschreibt, wie groß die Chance ist, dass ein bestimmtes Ereignis eintritt, zum Beispiel eine 6 beim Würfeln oder eine Farbe am Glücksrad.</p>
        <p>Man gibt sie als Bruch, Dezimalzahl oder Prozent an. Beim fairen Würfel ist die Chance für eine 6 gleich <strong>1/6</strong>, also etwa <strong>16,67&nbsp;%</strong>. Bei gleich wahrscheinlichen Ergebnissen spricht man von einem <strong>Laplace-Experiment</strong>.</p>
        <p>Wahrscheinlichkeit sagt nicht genau voraus, was der nächste Wurf bringt. Sie beschreibt, was man <em>langfristig</em> erwarten kann, wenn man ein Experiment oft wiederholt.</p>
        <ul>
          <li>Beispiel: Die Chance, eine 6 zu würfeln, ist 1 von 6.</li>
          <li>Wenn du 6-mal würfelst, erscheint die 6 ungefähr einmal.</li>
          <li>Mit der Wahrscheinlichkeit kannst du vorhersagen, wie oft ein Ergebnis auftreten könnte.</li>
        </ul>
      </div>
      <div id="zufallsversuche" class="info-box" style="display:none">
        <h2>Zufallsversuche</h2>
        <p>Ein Zufallsversuch ist ein Experiment, dessen Ergebnis du vorher nicht sicher kennst.</p>
        <p>Typisch sind Würfeln, Glücksrad drehen oder Karten ziehen. Jeder Durchgang liefert genau ein Ergebnis aus einer festen Menge möglicher Ergebnisse.</p>
        <p>Wiederholst du den Versuch oft, kannst du beobachten, welche Ergebnisse häufiger auftreten. So lernst du den Zufall nicht nur aus Rechnungen, sondern direkt beim Spielen kennen.</p>
        <ul>
          <li>Jeder Spielzug ist ein Zufallsversuch.</li>
          <li>Du kannst beobachten, welche Ergebnisse häufiger oder seltener vorkommen.</li>
        </ul>
      </div>
      <div id="baumdiagramme" class="info-box" style="display:none">
        <h2>Baumdiagramme</h2>
        <p>Ein Baumdiagramm ist eine Übersicht aller möglichen Ergebnisse eines Zufallsexperiments.</p>
        <p>Man startet an einem Punkt und verzweigt sich Schritt für Schritt. An jeder Kante steht oft die Wahrscheinlichkeit für den nächsten Zweig, zum Beispiel <strong>1/4</strong> für jede Farbe am Glücksrad.</p>
        <p>So siehst du auf einen Blick, welche Wege möglich sind und wie wahrscheinlich sie sind. Besonders hilfreich ist das, wenn mehrere Versuche hintereinander stattfinden.</p>
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
      <div class="step-panel">
        <h2>Einführung in die Stochastik</h2>
        <p>Willkommen in der spannenden Welt des Zufalls!</p>
        <p>Hier kannst du herausfinden, wie Wahrscheinlichkeiten funktionieren, warum manche Ergebnisse überraschend sind und wie man das alles spielerisch entdecken kann.</p>
        <p>In dieser Übung wirst du zuerst ein <strong>Glücksrad</strong> drehen und danach einen <strong>Würfel</strong> werfen. Am Ende schauen wir gemeinsam, was passiert ist.</p>
        <p>Beide Versuche sind <strong>Laplace-Experimente</strong> – jedes mögliche Ergebnis ist gleich wahrscheinlich.</p>
        <p>Bist du bereit?</p>
        <a href="?step=2"><button class="btn btn-spin">Los geht's!</button></a>
      </div>

      <!-- step 2: Drehrad - Baumdiagramm -->
      <?php elseif ($step == 2): ?>
      <div class="step-panel">
        <h2 style="text-align:center">Schritt 1: Glücksrad</h2>
        <p style="text-align:center">Dreh das Rad! Jede Farbe hat eine Chance von <strong>25 % (1/4)</strong>.</p>

        <div class="game-layout">
          <div>
            <svg width="220" height="380">
              <circle cx="40" cy="190" r="14" fill="#60a5fa"/>
              <text x="40" y="195" text-anchor="middle" fill="white" font-size="11" font-weight="bold">Start</text>

              <line id="line-1" x1="54" y1="183" x2="152" y2="62"  stroke="#5b6472" stroke-width="2"/>
              <text x="90"  y="108" text-anchor="middle" fill="#9ca3af" font-size="12">1/4</text>
              <line id="line-2" x1="54" y1="187" x2="152" y2="132" stroke="#5b6472" stroke-width="2"/>
              <text x="103" y="152" text-anchor="middle" fill="#9ca3af" font-size="12">1/4</text>
              <line id="line-3" x1="54" y1="193" x2="152" y2="248" stroke="#5b6472" stroke-width="2"/>
              <text x="103" y="228" text-anchor="middle" fill="#9ca3af" font-size="12">1/4</text>
              <line id="line-4" x1="54" y1="197" x2="152" y2="318" stroke="#5b6472" stroke-width="2"/>
              <text x="90"  y="272" text-anchor="middle" fill="#9ca3af" font-size="12">1/4</text>

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
              <button type="submit" name="spin" id="spin-btn" class="btn btn-spin"><?= $wheelResult > 0 ? 'Nochmal drehen!' : 'Drehen!' ?></button>
            </form>
            <div id="next-btn" style="display:none">
              <a href="?step=3"><button class="btn btn-next">Weiter zum Würfelspiel</button></a>
            </div>
          </div>
        </div>
      </div>

      <!-- step 3: Würfelspiel - Baumdiagramm -->
      <?php elseif ($step == 3): ?>
      <div class="step-panel">
        <h2 style="text-align:center">Schritt 2: Würfelspiel</h2>
        <p style="text-align:center">Wirf den Würfel! Jede Zahl hat eine Chance von <strong>16,67 % (1/6)</strong>.</p>

        <div class="game-layout">
          <div>
            <svg width="220" height="560">
              <circle cx="40" cy="280" r="14" fill="#60a5fa"/>
              <text x="40" y="285" text-anchor="middle" fill="white" font-size="11" font-weight="bold">Start</text>

              <line id="dline-1" x1="54" y1="272" x2="152" y2="50"  stroke="#5b6472" stroke-width="2"/>
              <text x="85"  y="140" text-anchor="middle" fill="#9ca3af" font-size="12">1/6</text>
              <line id="dline-2" x1="54" y1="276" x2="152" y2="140" stroke="#5b6472" stroke-width="2"/>
              <text x="100" y="196" text-anchor="middle" fill="#9ca3af" font-size="12">1/6</text>
              <line id="dline-3" x1="54" y1="279" x2="152" y2="220" stroke="#5b6472" stroke-width="2"/>
              <text x="108" y="242" text-anchor="middle" fill="#9ca3af" font-size="12">1/6</text>
              <line id="dline-4" x1="54" y1="281" x2="152" y2="310" stroke="#5b6472" stroke-width="2"/>
              <text x="108" y="305" text-anchor="middle" fill="#9ca3af" font-size="12">1/6</text>
              <line id="dline-5" x1="54" y1="284" x2="152" y2="400" stroke="#5b6472" stroke-width="2"/>
              <text x="100" y="372" text-anchor="middle" fill="#9ca3af" font-size="12">1/6</text>
              <line id="dline-6" x1="54" y1="288" x2="152" y2="490" stroke="#5b6472" stroke-width="2"/>
              <text x="85"  y="418" text-anchor="middle" fill="#9ca3af" font-size="12">1/6</text>

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
              <button type="submit" name="roll" id="roll-btn" class="btn btn-roll"><?= $diceResult > 0 ? 'Nochmal würfeln!' : 'Würfeln!' ?></button>
            </form>
            <div id="next-btn" style="display:none">
              <a href="?step=4"><button class="btn btn-next">Weiter zur Auswertung</button></a>
            </div>
          </div>
        </div>
      </div>

      <!-- step 4: auswertung -->
      <?php elseif ($step == 4): ?>
      <div class="step-panel">
        <h2>Auswertung</h2>
        <p>Du hast beide Zufallsversuche abgeschlossen. Hier ist, was passiert ist:</p>

        <div class="highlight-box">
          <p>In den Experimenten war zu erkennen, dass der Würfel geringere Wahrscheinlichkeiten für das jeweilige Ergebnis hatte, aber dafür 6 statt 4 verschiedene Ergebnisse zu erzielen waren.</p>
          <p>Obwohl der Würfel mehr verschiedene Ergebnisse hat als das Glücksrad, ist bei beiden Experimenten die jeweilige Chance immer der Anzahl der möglichen Ergebnisse entsprechend <strong>(= Laplace Experiment)!</strong></p>
        </div>

        <a href="?step=1"><button class="btn btn-spin">Nochmal von vorne</button></a>
      </div>
      <?php endif; ?>

    </div><!-- .content-area -->
  </div><!-- .stochastik-layout -->
</main>

<script>
var infoSections = ['wahrscheinlichkeit', 'zufallsversuche', 'baumdiagramme'];

// fnc sektion zeigen je ausgewahlt
function showSection(id, btn) {
    for (var i = 0; i < infoSections.length; i++) {
        document.getElementById(infoSections[i]).style.display = 'none';
    }
    document.getElementById(id).style.display = 'block';
    var btns = document.querySelectorAll('.topic-sidebar button');
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
    wctx.beginPath(); wctx.arc(200, 200, 150, 0, Math.PI * 2); wctx.strokeStyle = 'rgba(255,255,255,0.35)'; wctx.lineWidth = 3; wctx.stroke();
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
