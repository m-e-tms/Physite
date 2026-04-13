<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Aufgabe</title>
<meta name="author" content="joerg.ludwig">
<meta name="editor" content="html-editor phase 5">
</head>
<p>Zeit: <span id="timer">00:00</span></p>
 <?php
 session_start();
$solution = $_POST["solution"] ?? "";
$score = $_POST["score"] ?? 0;
$hide = $_POST["hide"] ?? 0;
$mode=$_POST["mode"]??0;

$level = intdiv(($score+$mode), 5) + 1;

if ($level <= 4) {
    $newTime = time() + 15;
}
elseif ($level <= 6) {
    $newTime = time() + 20;
}
elseif ($level <= 8) {
    $newTime = time() + 25;
}
elseif ($level <= 10) {
    $newTime = time() + 30;
}
elseif ($level <= 12) {
    $newTime = time() + 40;
}
elseif ($level <= 14) {
    $newTime = time() + 50;
}
elseif ($level <= 16) {
    $newTime = time() + 60;
}
elseif ($level <= 18) {
    $newTime = time() + 70;
}
elseif ($level <= 20) {
    $newTime = time() + 80;
}
elseif ($level <= 22) {
    $newTime = time() + 90;
}
elseif ($level <= 24) {
    $newTime = time() + 100;
}
elseif ($level <= 26) {
    $newTime = time() + 110;
}
else { // ab Level 27
    $newTime = time() + 120;
}
$endTime=$newTime;

//Kontrelle der Eingabe
if($hide!="1"){
if (isset($_POST['solution']) && isset($_SESSION['ergebnis'])) {
    $userSolution = trim($_POST['solution']);
    if ($userSolution == $_SESSION['ergebnis']) {
        $score++;
        echo "<p>korrekt</p>";}
    else {header("Location: endfeld_level.php?score=$score&mode=$mode");
        exit;}
    unset($_SESSION['ergebnis']); }}
echo "<p>Level: $level</p>";
//Timerblock:
?>

<script>
var endTime = Number("<?php echo $endTime; ?>");
var score = Number("<?php echo $score; ?>");
var mode = Number("<?php echo $mode; ?>");
function updateTimer() {
var currentTime = Math.floor(Date.now() / 1000);
var timeRemaining = endTime - currentTime;
if (timeRemaining <= 0) {
   document.getElementById("timer").innerHTML = "00:00";
   clearInterval(timerInterval);
   // Weiterleiten zum Endfeld inklusive Score
  window.location.href = "endfeld_level.php?score=" + score + "&mode=" + mode;
   return;}
var minutes = Math.floor(timeRemaining / 60);
var seconds = timeRemaining % 60;
if (seconds < 10) seconds = "0" + seconds;
   document.getElementById("timer").innerHTML = minutes + ":" + seconds;}
    updateTimer();
    var timerInterval = setInterval(updateTimer, 1000);
</script>


<?php
$operatoren=null;


if ($level == 1) {
    $zahl1 = rand(5,20);
    $zahl2 = rand(5, 20);

    if (rand(0,1) == 0) {
        $ergebnis = $zahl1 + $zahl2;
        $aufgabe = "$zahl1 + $zahl2";
    } else {
        if ($zahl2 > $zahl1) {
            [$zahl1, $zahl2] = [$zahl2, $zahl1];
        }
        $ergebnis = $zahl1 - $zahl2;
        $aufgabe = "$zahl1 - $zahl2";
    }
}
if ($level == 2) {
    $zahl1 = rand(51, 99);
    $zahl2 = rand(10, 17);

    if (rand(0,1) == 0) {
        $ergebnis = $zahl1 + $zahl2;
        $aufgabe = "$zahl1 + $zahl2";
    } else {
        if ($zahl2 > $zahl1) {
            [$zahl1, $zahl2] = [$zahl2, $zahl1];
        }
        $ergebnis = $zahl1 - $zahl2;
        $aufgabe = "$zahl1 - $zahl2";
    }
}
if ($level == 3) {
    $zahl1 = rand(75, 125);
    $zahl2 = rand(10, 17);

    if (rand(0,1) == 0) {
        $ergebnis = $zahl1 + $zahl2;
        $aufgabe = "$zahl1 + $zahl2";
    } else {
        if ($zahl2 > $zahl1) {
            [$zahl1, $zahl2] = [$zahl2, $zahl1];
        }
        $ergebnis = $zahl1 - $zahl2;
        $aufgabe = "$zahl1 - $zahl2";
    }
}
if ($level == 5) {
    $a = rand(50, 100);
    $b = rand(10, 30);
    $c = rand(10, 30);

    $ergebnis = $a - $b + $c;
    $aufgabe = "$a - $b + $c";
}

if ($level==4) {
$zahl1 = rand(3,15);
$zahl2 = rand(3,9);
$operatoren = ['*','/'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2;
        $aufgabe="$zahl1 * $zahl2";
        break;


    case '/':
        while ( $zahl1 % $zahl2 !== 0){
        $zahl1 = rand(3,24);
        $zahl2 = rand(11,24);
        }
        $ergebnis = $zahl1 / $zahl2;
        $aufgabe="$zahl1 / $zahl2";
        break;

    }

}
if ($level==6){
$zahl1 = rand(3,15);
$zahl2 = rand(7,15);
$operatoren = ['*','/'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2;
        $aufgabe="$zahl1 * $zahl2";
        break;
    case '/':
        while ( $zahl1 % $zahl2 !== 0){
        $zahl1 = rand(3,24);
        $zahl2 = rand(11,24);
        }
        $ergebnis = $zahl1 / $zahl2;
        $aufgabe="$zahl1 / $zahl2";
        break;


    }
}
if ($level==7){
$zahl1 = rand(3,24);
$zahl2 = rand(11,24);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2;
        $aufgabe="$zahl1 * $zahl2";
        break;


    }

}
if ($level==8){
$zahl1 = rand(11,19);
$zahl2 = rand(11,19);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2; $aufgabe="$zahl1 * $zahl2";
        break;


    }
}
if ($level==9){
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


    }  $aufgabe="$zahl1 * $zahl2 +  $zahl3 * $zahl4" ;
}
if ($level==10){
$zahl1 = rand(3,24);
$zahl2 = rand(11,24);
$zahl3 = rand(5,12);
$operatoren = ['*'];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '*':
        $ergebnis = $zahl1 * $zahl2 + $zahl3;  $aufgabe="$zahl1 $operator $zahl2 + $zahl3";
        break;


    }
}
if ($level==11){
$zahl1 = rand(150,1000);
$zahl2 = rand(11,15);
$operatoren = ['/',];
$operator = $operatoren[array_rand($operatoren)];
switch ($operator) {

    case '/':
        while ( $zahl1 % $zahl2 !== 0){
        $zahl1 = rand(1000,1500);
        $zahl2 = rand(11,15);
        }
        $ergebnis = $zahl1 / $zahl2;     $aufgabe="$zahl1 / $zahl2";

        break;

       }

    }
if ($level==12){
$zahl1 = rand(3,9);
$zahl2 = rand(11,24);


$ergebnis = ($zahl1 + $zahl2)*($zahl1 + $zahl2);
$aufgabe="( $zahl1 + $zahl2 )^2";
}
if ($level==13){
$zahl1 = rand(15,29);
$zahl2 = rand(11,24);


$ergebnis = ($zahl1 + $zahl2)*($zahl1 + $zahl2);
$aufgabe="( $zahl1 + $zahl2 )<sup>2</sup>";
}
if($level >= 14){
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
echo "<input type='hidden' name='mode' value='$mode'>";
echo "   <input type='submit' value='Senden'>";
echo "<input type='hidden' name='hide' value=''>";
echo "</p></form>";
?>
<p class="neustart"><a href="startfeld.html">neu starten</a></p>
</div></body>
</html>