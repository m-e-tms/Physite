<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Ende</title>
<meta name="author" content="joerg.ludwig">
<meta name="editor" content="html-editor phase 5">
<body>
</head>
<div class="end-screen">
<h1>Spiel beendet...</h1>
<p align="center">Die Eingabe war falsch oder die Zeit ist abgelaufen.</p></p>
<div class="stats">
<?php
$score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
$mode = isset($_GET["mode"]) ? intval($_GET["mode"]) : 0;
$score = $score-1;
$level = intdiv(($score+$mode), 5) + 1;
echo "<p>Erreichtes Level: $level mit $score Punkten</p>";
?></div>

<div class="buttons">
    <a href="startfeld.html">Neu starten</a>
  </div>  </div>


</body>
</html>