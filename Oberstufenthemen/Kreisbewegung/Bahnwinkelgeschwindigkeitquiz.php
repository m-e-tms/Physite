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
    <h1>Quiz zur Bahn- und Winkelgeschwindigkeit</h1>
<div class="widget">
    <h2>Der Jahrmarktbesuch</h2>
    <p align="left"> Anton, Babara und Claus besuchen den Jahrmarkt. Dort sehen die Drei kleines Kinder-Karussel. Der Umfang dieses Karussells beträgt ca. 12m mit einem Radius von ca. 2m. Anton nimmt an einer Fahrt teil, wobei er sich ganz außen auf das Karussel setzt. Barbara läuft mit einem Abstand von 4 Metern neben Anton her. Claus will wissen, wie schnell beide sind. Er stoppt misst die Fahrtzeit mit einer Stoppuhr. Eine Karussellfahrt dauert 3 Minuten. Dabei dreht das Karussell 9 Runden. </p>
<?php
session_start();
$solution = $_POST["answer"] ??0;
$score = $_POST["score"] ??0;
$tasknumber = $_POST["tasknumber"] ??1;
$answercorrect1="";//bei bedarf entfernen (Wenn ohne anzeigung der richtigen lösung nach fehler)
$solutioncorrect="";
$task="";
$quizend=0;
if($tasknumber==1){
    $task="<p>Wie groß ist die Periodendauer?  Gib die Lösung als Zahl in Sekunden an (ohne Buchstaben der Einheit).</p>";
    $solutioncorrect ="20"; 
    $answercorrect1=" 20 - Periodendauer T = 3·60s÷9 = 20s";
}
elseif($tasknumber==2){
    $task="<p>Berechne die Bahngeschwindigkeit mit der sich Anton auf dem Karussell bewegt. (T=20s) Gib die Lösung als Dezimalzahl in der Einheit Meter pro Sekunde an (ohne Buchstaben der Einheit).</p>";
    $solutioncorrect ="0,6"; 
    $answercorrect1=" 0,6 - Bahngeschwindigkeit v = U÷T = 12m ÷ 20s = 0,6 m/s";
}
elseif($tasknumber==3){
    $task="<p>Welche Winkelgeschwindigkeit hat demnach das Karussel? (Karussell: v=0,6 m/s) Gib die Lösung in der Einheit pro Sekunde an (ohne Buchstaben der Einheit).</p>";
    $solutioncorrect ="0,3"; 
    $answercorrect1=" 0,3 - v = ω·r => ω = v÷r = 0,6 m/s ÷ 2m = 0,3 1/s"; 
}
elseif($tasknumber==4){
    $task="<p>Bestimme anhand der Winkelgeschdigkeit des Karussels (ω=0,3 1/s) die Bahngeschwindigkeit von Babara. Gib die Lösung in der Einheit Meter pro Sekunde an (ohne Buchstaben der Einheit).</p>";
    $solutioncorrect ="1,8"; 
    $answercorrect1=" 1,8 - r<sub>Babara</sub> = 2m + 4m = 6m    v = ω·r = 0,3 1/s · 6m = 1,8 m/s"; 
}
elseif($tasknumber>4){
    $quizend=1;$tasknumber=$tasknumber-1;
} 
if (isset($_POST['answer']) && isset($_SESSION['solutioncorrect'])) {
    $userSolution = trim($solution);
    if ($userSolution == $_SESSION['solutioncorrect']) {
        $score++;
        echo "<b><font color='#00FF00'><p>korrekt</p></font></b>";
    }
    else {
        echo "<b><font color='#FF0000'><p>falsch</p>
        <p>Die richtige Lösung wäre:" . $_SESSION['answercorrect'] . "</p>
        </font></b>";
    }    
}
unset($_SESSION['solutioncorrect']);
unset($_SESSION['answercorrect']);
echo "<p>Punkte: $score</p>";
if($quizend==0){$_SESSION['solutioncorrect'] = $solutioncorrect;
    $_SESSION['answercorrect']=$answercorrect1;//diese Zeile optional raustreichen
    }
    echo "<p>Aufgabe: $tasknumber</p>";
if($quizend==0){
echo $task;
    $tasknumber++;
}
if($quizend==0){

    echo "<form action='Bahnwinkelgeschwindigkeitquiz.php' method='post'>"; 

    echo" <input type='hidden' name='score' value='$score'> ";
    echo" <input type='hidden' name='tasknumber' value='$tasknumber'> ";

    echo "<div class='inputtext'><input type='text' name='answer' autofocus></div>";

    echo "<div class='buttons'>
            <button type='submit'>Button um zu bestätigen</button>
          </div>";

    echo "</form>";
}
else{

    echo "<form action='Bahnwinkelgeschwindigkeit.php' method='post'>"; 

    echo" <input type='hidden' name='score' value='$score'> ";
    echo" <input type='hidden' name='tasknumber' value='$tasknumber'> ";

    echo "<div class='buttons'>
            <button type='submit'>Quizabschließen</button>
          </div>";

    echo "</form>";
}
?>
</div>
</div>
</body>
</html>