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
<h1>Spiel beendet...</h1>
<b><font color='#FF0000'><p align="center">Die Eingabe war falsch oder die Zeit ist abgelaufen.</p></p></font></b>

<?php
$score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
$mode = isset($_GET["mode"]) ? intval($_GET["mode"]) : 0;
$score = $score-1;
$level = intdiv(($score+$mode), 5) + 1;
echo "<p>Erreichtes Level: $level mit $score Punkten</p>";
?></div>


    <a href="startfeld.html">Neu starten</a>

</div>

</body>
</html>