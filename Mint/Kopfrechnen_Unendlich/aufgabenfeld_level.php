<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Aufgabe</title>
<meta name="author" content="joerg.ludwig">
<meta name="editor" content="html-editor phase 5">
</head>
<body><div class="container">
<style>
/* =============================
   BASIS – Design 1 (zentriert)
   ============================= */

    body {
        font-family: 'Arial', sans-serif;
        background: #9ac7e3;
        color: #222;
        margin: 0;
        padding: 0;
        /* Zentrierung */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        text-align: center;
    }

/* Hauptcontainer */
.container {
    width: 100%;
    max-width: 600px;
    background: #ffffff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* Text */
p {
    font-size: 20px;
    margin: 15px 0;
}

/* Zeit – NUR Zahlen rot */
.time-digits {
    color: #e63946;
    font-weight: bold;
    font-size: 22px;
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
    background: #457b9d;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

    input[type="submit"]:hover {
        background: #5e368c;
    }

/* Link */
a {
    color: #457b9d;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* bestehendes CSS bleibt */

/* Einheitliche Textgröße für normalen Text */
p {
    font-size: 20px;
    margin: 15px 0;
}

/* Aufgabe extra groß */
.aufgabe {
    font-size: 36px;
    font-weight: bold;
    margin: 30px 0;
}
.neustart {
    margin-top: 30px;
}
</style>
<p>Zeit: <span id="timer">00:00</span></p>
 <?php
 session_start();
$solution = $_POST["solution"];
$score =$_POST["score"];
$hide = $_POST["hide"];

//Errechnen des Levels und der dazughoerigen Zeit
$level = intdiv($score, 5) + 1;

if ($level <= 9) {
    $newTime = time() + intdiv($level-1, 2)*10+10 ;
}
elseif ($level >= 10) {
    $newTime = time() + 60;
}
$endTime=$newTime;

//Kontrelle der Eingabe
if($hide!="1"){
if (isset($_POST['solution']) && isset($_SESSION['ergebnis'])) {
    $userSolution = trim($_POST['solution']);
    if ($userSolution == $_SESSION['ergebnis']) {
        $score++;
        echo "<p>korrekt</p>";}
    else {header("Location: endfeld_level.php?score=$score");
        exit;}
    $_SESSION['score'] = $score;
    unset($_SESSION['ergebnis']); }}
echo "<p>Level: $level</p>";
//Timerblock:
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
   // Weiterleiten zum Endfeld inklusive Score
   window.location.href = "endfeld_level.php?score=" + score;
   return;}
var minutes = Math.floor(timeRemaining / 60);
var seconds = timeRemaining % 60;
if (seconds < 10) seconds = "0" + seconds;
   document.getElementById("timer").innerHTML = minutes + ":" + seconds;}
    updateTimer();
    var timerInterval = setInterval(updateTimer, 1000);
</script>


<?php
//Level haben versch Aufgaben
$operatoren=null;
if ($level==1) {
$zahl1 = rand(3,15);
$zahl2 = rand(3,9);
$operatoren = ['*','/'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2;
        $aufgabe="$zahl1 \u{00D7} $zahl2";
        break;


    case '/':
        while ( $zahl1 % $zahl2 !== 0){
        $zahl1 = rand(10,35);
        $zahl2 = rand(3,9);
        }
        $ergebnis = $zahl1 / $zahl2;
        $aufgabe="$zahl1 \u{00F7} $zahl2";
        break;

    }

}
if ($level==2){
$zahl1 = rand(3,24);
$zahl2 = rand(11,24);
$operatoren = ['*','/'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2;
        $aufgabe="$zahl1 \u{00D7} $zahl2";
        break;
    case '/':
        while ( $zahl1 % $zahl2 !== 0){
        $zahl1 = rand(24,50);
        $zahl2 = rand(6,19);
        }
        $ergebnis = $zahl1 / $zahl2;
        $aufgabe="$zahl1 \u{00F7} $zahl2";
        break;


    }
}
if ($level==3){
$zahl1 = rand(3,24);
$zahl2 = rand(11,24);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2;$aufgabe="$zahl1 \u{00D7} $zahl2";
        break;


    }

}
if ($level==4){
$zahl1 = rand(11,19);
$zahl2 = rand(11,19);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2; $aufgabe="$zahl1 \u{00D7} $zahl2";
        break;


    }
}
if ($level==5){
$zahl1 = rand(3,15);
$zahl2 = rand(3,15);
$zahl3 = rand(3,15);
$zahl4 = rand(3,15);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis =( $zahl1 * $zahl2) +( $zahl3 * $zahl4);
        break;


    }  $aufgabe="$zahl1 \u{00D7} $zahl2 +  $zahl3 \u{00D7} $zahl4" ;
}
if ($level==6){
$zahl1 = rand(3,24);
$zahl2 = rand(11,24);
$zahl3 = rand(5,12);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2 + $zahl3;  $aufgabe="$zahl1 \u{00D7} $zahl2 + $zahl3";
        break;


    }
}
if ($level==7){
$zahl1 = rand(150,1000);
$zahl2 = rand(11,15);
$operatoren = ['/',];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '/':
        while ( $zahl1 % $zahl2 !== 0){
        $zahl1 = rand(1000,1500);
        $zahl2 = rand(3,20);
        }
        $ergebnis = $zahl1 / $zahl2;     $aufgabe="$zahl1 \u{00F7} $zahl2";

        break;

       }

    }
if ($level==8){
$zahl1 = rand(3,9);
$zahl2 = rand(11,24);


$ergebnis = ($zahl1 + $zahl2)*($zahl1 + $zahl2);
$aufgabe="( $zahl1 + $zahl2 )^2";
}
if ($level==9){
$zahl1 = rand(15,29);
$zahl2 = rand(11,24);


$ergebnis = ($zahl1 + $zahl2)*($zahl1 + $zahl2);
$aufgabe="( $zahl1 + $zahl2 )^2";
}
if($level >= 10){
echo "<p align='left'>Es gilt keine Punkt-Vor-Strichrechnung!</p> ";
$anzahlGlieder = 1 + floor(($level - 1) / 3);
$operatoren = ['*','/']; // Basis-Operatoren

// Ab dem 3. Rechenglied (+ und - hinzufügen)
if ($anzahlGlieder >= 3) {
    $operatoren[] = '+';
    $operatoren[] = '-';
}


$min = 3;
$max = 9 + $level * 2;

$zahl1 = rand($min, $max);
$zahl2 = rand($min, $max);

$operator = $operatoren[array_rand($operatoren)];

switch ($operator) {
    case '*':
        $ergebnis = $zahl1 * $zahl2;
        break;

    case '/':
        // sichere Division, garantiert ganzzahliges Ergebnis
        $versuche = 0;
        do {
            $zahl2 = rand($min, $max);
            $versuche++;
            if ($zahl1 % $zahl2 === 0) break;
            if ($versuche > 100) { $zahl2 = 1; break; }
        } while (true);

        $ergebnis = $zahl1 / $zahl2;
        break;

    case '+':
        $ergebnis = $zahl1 + $zahl2;
        break;

    case '-':
        $ergebnis = $zahl1 - $zahl2;
        break;
}

$aufgabe = "$zahl1 $operator $zahl2";

$glue = 1;
while ($glue < $anzahlGlieder) {

    $zahl = rand($min, $max);
    $op = $operatoren[array_rand($operatoren)];

    switch ($op) {

        case '*':
            $ergebnis = $ergebnis * $zahl;
            break;

        case '/':
            $moeglicheTeiler = [];
for ($i = $min; $i <= $max; $i++) {
    if ($i != 0 && $ergebnis % $i === 0) {
        $moeglicheTeiler[] = $i;
    }
}

// Teiler auswählen
if (count($moeglicheTeiler) > 0) {
    $zahl = $moeglicheTeiler[array_rand($moeglicheTeiler)];
} else {
    $zahl = 1; // Notlösung, keine Kommazahlen
}

$ergebnis = $ergebnis / $zahl;
            break;

        case '+':
            $ergebnis = $ergebnis + $zahl;
            break;

        case '-':
            $ergebnis = $ergebnis - $zahl;
            break;
    }

    $aufgabe .= " $op $zahl";
    $glue++;
}}

 echo "<p class='aufgabe'>Aufgabe: $aufgabe = ?</p>";

$_SESSION['ergebnis'] = $ergebnis;
 //Eingabe und Datenuebertragfung
echo "<form action='aufgabenfeld_level.php' method='post'>";
echo "<p>Eingabe:   ";
echo "<input type='text' name='solution' autofocus>";
echo "<input type='hidden' name='score' value='$score'>";
echo "   <input type='submit' value='Senden'>";
echo "<input type='hidden' name='hide' value=''>";
echo "</p></form>";
?>
<p class="neustart"><a href="startfeld.html">Neu starten</a></p>
</div></body>
</html>