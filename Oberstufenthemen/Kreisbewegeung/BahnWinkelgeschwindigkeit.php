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
  <h1>Bahn- und Winkelgeschwindigkeit</h1>
  <div class="widget">
    <h2>Einleitung</h2>
    <p align="left">Manchmal bewegen sich Objekte auf kreisförmigen Bahnen. Die Bewegung dieser Objekte können wir physikalisch beschreiben. Auf dieser Seite setzen wir uns mit Periodendauer, Bahn- und Winkelgeschwindigkeit auseinander.</p>
    <p align="left">Die Periodendauer, auch als Umlaufzeit bezeichnet, beschreibt dabei die Zeit, in der das Objekt die gesamte Kreisbahn einmal umläuft.</p>
    <p align="left">Um die Geschwindigkeit zu beschreiben, gibt zwei unterschiedliche Möglichkeiten. Erstens, die Bahngeschwindigkeit. Diese entspricht der klassischen Geschwindigkeit als Strecke pro Zeit. Hierfür muss man den Umfang der Kreisbahn berechnen. Diese entspricht der in der Periodendauer zurückgelegten Strecke. </p>
    <p align="left">Zweitens, die Winkelgeschwindigkeit. Diese beschreibt die Bewegung als Winkel pro Zeit. Man kann an ihr also nicht ablesen, welche Distanz ein Objekt zurücklegt (bzw. nur indirekt), sondern nur die Länge der zurückgelegten Strecke im Verhältnis zum Kreis. In der Physik wird hierfür i.d.R. das Bogenmaß verwendet (360°=2 &#960). Alle drei Größen sind unten mit Formelzeichen, Formel und Einheit aufgelistet.</p>
    
    <table border="1" cellspacing="0" cellpadding="5" align="center">
  <thead>
    <tr>
      <th>Größe</th>
      <th>Formelzeichen</th>
      <th>Formel</th>
      <th>Einheit</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Periodendauer / Umlaufzeit</td>
      <td>T</td>
      <td>/</td>
      <td>s</td>
    </tr>
    <tr>
      <td>Bahngeschwindigkeit</td>
      <td>v</td>
      <td>v = U / T = 2πr / T<br>v = ω · r</td>
      <td>m/s</td>
    </tr>
    <tr>
      <td>Winkelgeschwindigkeit</td>
      <td>ω</td>
      <td>ω = 2π / T</td>
      <td>1/s</td>
    </tr>
  </tbody>
</table>
<p align="left">Hinweis: ω ist der kleine griechische Buchstabe "Omega".</p>

  </div>
  <div class="widget">
    <h2>Quiz</h2>
<?php
$score = $_POST["score"] ?? $_GET["score"] ?? 0; 
$tasknumber = isset($_POST['tasknumber']) ? $_POST['tasknumber'] : (isset($_GET['tasknumber']) ? $_GET['tasknumber'] - 1 : 0);
echo "<p>Du hast das Quiz abgeschlossen.</p>";
echo "<p>Punkte: $score</p>";
echo "<p>Bearbeitete Aufgaben: $tasknumber von 4</p>";
?>


<form action="Bahnwinkelgeschwindigkeitquiz.php" method="post">  <!-- Php Seitennamen einfügen -->
    <input type="hidden" name="score" value="0">
    <input type="hidden" name="tasknumber" value="1">
    <div class="buttons">
    <button type="submit">Quiz starten</button>
    </div>
</form>

</div>
<br>
</body>
</html>