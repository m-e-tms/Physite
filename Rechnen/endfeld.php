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
    <?php
    session_start();
    $score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
    echo "<p>Punkte: $score</p>";
    ?>
  </div> <a href="startfeld.html">Neu starten</a>
</div>
</body>
</html>