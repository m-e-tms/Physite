<!DOCTYPE html>
<html>
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

<div class="card">
    <h1>Supercooles Quiz</h1>
    <p align="left">Hier irgendein genereller Text.</p>
    <canvas></canvas>



<div class="widget">
<?php
//Körper des Php-Quizes
session_start();

$solution = $_POST["answer"] ??0;
$score = $_POST["score"] ??0;
$tasknumber = $_POST["tasknumber"] ??1;

$answercorrect1="";//bei bedarf entfernen (Wenn ohne anzeigung der richtigen lösung nach fehler)
$solutioncorrect="";
$task="";
$quizend=0;

//Füge Hier die Aufgaben, richtige Lösungen als Nummer und alle Antwortmöglichkeiten (als Text in "") ein
// Du brauchst keine Antwortmöglichkeiten, wenn du mit einem Textfeld arbeitest
//Passe $tasknumber an (Aufgabennummer)
if($tasknumber==1){
    $task="<p>Hier dein Aufgaben text.</p>";
    $solutioncorrect =1; //Richtige Lösung einfügen//Wenn du das Textfeld verwendest, dann in "" gewünschte Eingabe angeben.
    $antwort1="Antwortmöglichkeit1";
    $antwort2="Antwortmöglichkeit2";
    $antwort3="Antwortmöglichkeit3";
    $antwort4="Antwortmöglichkeit4";
    $answercorrect1="angezeigte Lösung mit Erklärung"; //optional raustreichen
}

elseif($tasknumber==2){
    $task="<p>Hier dein Aufgaben text.</p>";
    $solutioncorrect =3; //Richtige Lösung einfügen
    $antwort1="Antwortmöglichkeit1";
    $antwort2="Antwortmöglichkeit2";
    $antwort3="Antwortmöglichkeit3";
    $antwort4="Antwortmöglichkeit4";
    $answercorrect1="angezeigte Lösung mit Erklärung"; //optional raustreichen
}

elseif($tasknumber==3){
    $task="<p>Hier dein Aufgaben text.</p>";
    $solutioncorrect =4; //Richtige Lösung einfügen
    $antwort1="Antwortmöglichkeit1";
    $antwort2="Antwortmöglichkeit2";
    $antwort3="Antwortmöglichkeit3";
    $antwort4="Antwortmöglichkeit4";
    $answercorrect1="angezeigte Lösung mit Erklärung"; //optional raustreichen
}

//usw.

elseif($tasknumber>3){
    $quizend=1;$tasknumber=$tasknumber-1;
} //Gib hier unbedingt die Nummer deiner letzten Aufgab ein  (in die Klammer von elseif)



 //Kontrolle der Lösungen
if (isset($_POST['answer']) && isset($_SESSION['solutioncorrect'])) {
    $userSolution = trim($solution);
    if ($userSolution == $_SESSION['solutioncorrect']) {
        $score++;
        echo "<b><font color='#00FF00'><p>korrekt</p></font></b>";
    }
    else {
        echo "<b><font color='#FF0000'><p>falsch</p>
        <p>Die richtige Lösung wäre:" . $_SESSION['answercorrect'] . "</p><!--//diese Zeile optional raustreichen-->
        </font></b>";
    }

    
}
unset($_SESSION['solutioncorrect']);
unset($_SESSION['answercorrect']);
if($quizend==0){echo "<font color='#FF8000'><p>Zeit: <span id='timer'>00:00</span></p></font>";}  //Diese Zeile entfernen, wenn ohne Timer verwendet.
echo "<p>Punkte: $score</p>";


//gesamten <script> blockentfernen, wenn ohne timer verwendet
//passe den var duration block an, um die dauer zu 
if($quizend==0){
?>
<script>
var duration = 5;
var endTime = Math.floor(Date.now() / 1000) + duration;
function updateTimer() {
    var currentTime = Math.floor(Date.now() / 1000);
    var timeRemaining = endTime - currentTime;

    if (timeRemaining <= 0) {
        window.location.href = "Vorlagehtml.php?score=<?php echo $score; ?>&tasknumber=<?php echo $tasknumber; ?>";
        return;
    }

    var minutes = Math.floor(timeRemaining / 60);
    var seconds = timeRemaining % 60;
    if (seconds < 10) seconds = "0" + seconds;

    var timerEl = document.getElementById("timer");
    if (timerEl) {
        timerEl.innerHTML = minutes + ":" + seconds;
    }
}

updateTimer();
setInterval(updateTimer, 1000);
</script>



<?php
}


//Anzeige diverse
if($quizend==0){$_SESSION['solutioncorrect'] = $solutioncorrect;
    $_SESSION['answercorrect']=$answercorrect1;//diese Zeile optional raustreichen
    }
    echo "<p>Aufgabe: $tasknumber</p>";
if($quizend==0){
echo $task;
    $tasknumber++;
}

if($quizend==0){

    echo "<form action='Vorlagequizphp.php' method='post'>"; // Php-Seitennamn dieser Seite selbst einfüen einfügen

    echo" <input type='hidden' name='score' value='$score'> ";
    echo" <input type='hidden' name='tasknumber' value='$tasknumber'> ";

    //Antwort über Textfeld
    echo "<div class='inputtext'><input type='text' name='answer' autofocus></div>";

    echo "<div class='buttons'>
            <button type='submit'>Button um zu bestätigen</button>
          </div>";

    //Anrtwort über Buttons
    echo "<div class='buttons'>";

    echo " <button type='submit' name='answer' value='1'>$antwort1</button>";
    echo " <button type='submit' name='answer' value='2'>$antwort2</button>";
    echo " <button type='submit' name='answer' value='3'>$antwort3</button>";
    echo " <button type='submit' name='answer' value='4'>$antwort4</button>";

    echo "</div>";

    //Wähle entweder Buttons oder Textfeld als Eingabe für dn User
    echo "</form>";
}

else{

    echo "<form action='Vorlagehtml.php' method='post'>"; //Seite des Abschlusses einfügen

    echo" <input type='hidden' name='score' value='$score'> ";
    echo" <input type='hidden' name='tasknumber' value='$tasknumber'> ";

    echo "<div class='buttons'>
            <button type='submit'>Quizabschließen</button>
          </div>";

    echo "</form>";
}
?>

<p align="left">Gebrauchsanweisung</p>
</div> </div>
<br>


</body>
</html>