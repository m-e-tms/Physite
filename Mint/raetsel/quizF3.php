<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Antwort auf 3. Frage</title>
<meta name="author" content="joerg.ludwig">

<meta name="editor" content="html-editor phase 5">
</head>
<body text="#000000" bgcolor="#FFFFFF" link="#FF0000" alink="#FF0000" vlink="#FF0000">
<?php
         $antwort=$_POST["Antwort"];
         if ($antwort=="a")
                 {
                 header("location: quizF4.html");
                 }
         else
                 {
                 header("location: quizFalsch.php");
                 }

?>
</body>
</html>