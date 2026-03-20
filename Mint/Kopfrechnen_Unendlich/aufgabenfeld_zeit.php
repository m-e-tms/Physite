<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Aufgabe</title>
<meta name="author" content="">
<meta name="editor" content="html-editor phase 5">
<style>
/* ============================= */
/* Basis Design – Lila Version   */
/* ============================= */

    body {
        font-family: 'Arial', sans-serif;
        background: #ecc2f0; /* helllila Hintergrund */
        color: #222;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        text-align: center;
    }

.container {
    width: 100%;
    max-width: 600px;
    background: #ffffff; /* Container bleibt weiß */
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(128, 0, 128, 0.2); /* leichter lila Schatten */
}

/* Normale Texte */
p {
    font-size: 20px;
    margin: 15px 0;
}

/* Zeit – Zahlen lila */
.time-digits {
    color: #800080; /* dunkel-lila */
    font-weight: bold;
    font-size: 22px;
}

/* Aufgabe extra groß */
.aufgabe {
    font-size: 36px;
    font-weight: bold;
    margin: 30px 0;
}

/* Eingabe */
input[type="text"] {
    padding: 10px;
    font-size: 18px;
    width: 160px;
    text-align: center;
    border: 2px solid #800080;
    border-radius: 6px;
}

/* Button */
input[type="submit"] {
    padding: 10px 22px;
    font-size: 16px;
    background: #800080; /* lila */
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

    input[type="submit"]:hover {
        background: #5e368c; /* dunkleres lila */
    }

/* Link */
a {
    color: #800080;
    text-decoration: none;
    font-weight: bold;
}

/* Hover Link */
a:hover {
    text-decoration: underline;
}

/* Abstand für „neu starten“ identisch mit Aufgabe */
.neustart {
    margin-top: 30px;
}
</style>
</head>
<body>
<div class="container">

<p>Zeit: <span id="timer" class="time-digits">00:00</span></p>

<?php
session_start();
$solution = $_POST["solution"];
$score = $_POST["score"];
$endTime = $_POST["endTime"];
$hide = $_POST["hide"];

// Kontrolle der Eingabe
if($hide=="1"){ $endTime = time() + 30; }
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
// Aufgabe mit Losung importieren
$file = fopen("aufgabenliste.txt", "r");
$line_number = rand(1, 542)*2-1;
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

<p class="neustart"><a href="startfeld.html">Neu starten</a></p>
</div>
</body>
</html>