<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Endfeld</title>
<meta name="author" content="joerg.ludwig">
<meta name="editor" content="html-editor phase 5">
</head>
<body>
<div class="end-screen">
  <h1>Spiel beendet...</h1>

  <div class="stats">
    <?php
    session_start();

if (isset($_SESSION['user_id'])) {
    echo "Eingeloggt als User-ID: " . $_SESSION['user_id'];
}    //soll user_id herausfinden

require "platzhalterfuerdb.php"; // Verbindung
$user_id = $_SESSION['user_id'];


    $score = isset($_GET["score"]) ? intval($_GET["score"]) : 0;
    echo "<p>Punkte: $score</p>";

$sql = "UPDATE users
        SET score = $score
        WHERE id = $user_id
        AND score < $score";
mysqli_query($conn, $sql);
    ?>
  </div>

  <div class="buttons">
    <a href="startfeld.html">Neu starten</a>
  </div>
</div>


</body>
</html>