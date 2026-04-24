<!DOCTYPE html>
<html>
    <title>Aufgabe</title>
<head>

  <meta charset="UTF-8">
  <link rel="stylesheet" href="test.css">

</head>
<body>

  <nav>
    <div class="element">Home</div>
    <div class="element">Scoreboard</div>
    <div class="element">News</div>
    <div class="element no-border dropdown">
      Dropdown
      <div class="dropdown-content">
        <button>Link 1</button>
        <button>Link 2</button>
        <button>Link 3</button>
      </div>
    </div>
  </nav>

<div class="card"><p>Progressivmodus:</p>
<div class="widget">
<font color='#FF8000'><p>Zeit: <span id="timer" class="time-digits">00:00</span></p></font>

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
            echo "<b><font color='#00FF00'><p>korrekt</p></font></b>";
        } else {
            echo "<b><font color='#FF0000'><p>falsch</p></font></b>";
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
echo "<p>Eingabe:   ";
echo "<input type='text' name='solution' autofocus>";
echo "<input type='hidden' name='score' value='$score'>";
echo "  <div class='buttons'><input type='submit' value='Senden'>";
echo "</div><input type='hidden' name='endTime' value='$endTime'>";
echo "<input type='hidden' name='hide' value=''>";
echo "</p></form>";
?>
</div>
<div class="buttons">
    <a href="startfeld.html" class="button-link">Neu starten</a>
  </div>
</div></div>
</body>
</html>