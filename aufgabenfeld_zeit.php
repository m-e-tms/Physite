<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Aufgabe</title>
<meta name="author" content="">
<meta name="editor" content="html-editor phase 5">
</head>
<body>
<p>Zeit: <span id="timer" class="time-digits">00:00</span></p>

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
            echo "<p>korrekt</p>";
        } else {
            echo "<p>falsch</p>";
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
echo "  <input type='submit' value='Senden'>";
echo "<input type='hidden' name='endTime' value='$endTime'>";
echo "<input type='hidden' name='hide' value=''>";
echo "</p></form>";
?>

<div class="buttons">
    <a href="startfeld.html" class="button-link">Neu starten</a>
  </div>
</div>
</body>
</html>