<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aufgabe</title>
  <link rel="stylesheet" href="../../../resources/Vorlage_Standard/test.css">
  <link rel="stylesheet" href="kopfrechnen.css">
<link rel="stylesheet" href="../../../resources/nav/physite-nav.css" data-physite-nav-css>
<script src="../../../resources/nav/physite-nav.js" defer data-physite-nav></script>
</head>
<body>

  <nav class="physite-nav" aria-label="Physite"></nav>

<div class="card">
<p class="mode-label">Zeitmodus</p>
<div class="widget">
<p class="timer-line">Zeit: <span id="timer" class="time-digits">00:00</span></p>

<?php
session_start();
$solution = $_POST["solution"] ??0;
$score = $_POST["score"] ??0;
$endTime = $_POST["endTime"]??0;
$hide = $_POST["hide"]??0;

// Kontrolle der Eingabe
if($hide=="1"){ $endTime = time() + 45 ; }
else {
    if (isset($_POST['solution']) && isset($_SESSION['solutioncorrect'])) {
        $userSolution = trim($_POST['solution']);
        if ($userSolution == $_SESSION['solutioncorrect']) {
            $score++;
            echo "<p class='status-ok'>korrekt</p>";
        } else {
            echo "<p class='status-bad'>falsch</p>";
        }
        $_SESSION['score'] = $score;
        unset($_SESSION['solutioncorrect']);
    }
}
echo "<p>Punkte: $score</p>";
?>

<script>
var endTime = Number("<?php echo $endTime; ?>");
var score = Number("<?php echo $score; ?>");
function updateTimer() {
    var currentTime = Math.floor(Date.now() / 1000);
    var timeRemaining = endTime - currentTime;
    if (timeRemaining <= 0) {
        document.getElementById("timer").innerHTML = "00:00";
        clearInterval(timerInterval);
        window.location.href = "endfeld.php?score=" + score;
        return;
    }
    var minutes = Math.floor(timeRemaining / 60);
    var seconds = timeRemaining % 60;
    if (seconds < 10) seconds = "0" + seconds;
    document.getElementById("timer").innerHTML = minutes + ":" + seconds;
}
updateTimer();
var timerInterval = setInterval(updateTimer, 1000);
</script>

<?php
// Aufgabe importieren
$file = fopen("aufgabenliste.txt", "r");
$line_number = rand(1, 334)*2-1;
for ($i = 1; $i < $line_number; $i++) { fgets($file); }
$task = fgets($file);
$solutioncorrect = trim(fgets($file));
$_SESSION['solutioncorrect'] = $solutioncorrect;
fclose($file);

// Aufgabe ausgeben
echo "<p class='aufgabe'>$task</p>";

// Eingabeformular
echo "<form action='aufgabenfeld_zeit.php' method='post'>";
echo "<div class='answer-row'>";
echo "<input type='text' name='solution' autofocus placeholder='Antwort'>";
echo "<input type='hidden' name='score' value='$score'>";
echo "<button type='submit' class='kopfrechnen-btn'>Senden</button>";
echo "<input type='hidden' name='endTime' value='$endTime'>";
echo "<input type='hidden' name='hide' value=''>";
echo "</div></form>";
?>
</div>
<div class="restart-row">
    <a href="startfeld.html" class="button-link">Neu starten</a>
</div>
</div>
</body>
</html>