<!-- Version 1.06 -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Glücksrad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            padding: 20px;
        }
        h1 {
            font-size: 48px;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        button {
            font-size: 24px;
            padding: 15px 40px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 20px 0;
        }
        img {
            margin: 20px auto;
            display: block;
        }
        h2 {
            font-size: 36px;
            color: #333;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Glücksrad</h1>
    <p class="subtitle">In der folgenden Anwendung probierst du ein Glücksrad aus.
    <br></br>Dies ist ein Laplace Experiment, d.h., dass alle möglichen Ergebnisse die gleiche Wahrschheinlichkeit besitzen.
    <br></br>Jede Farbe hat eine Chance von 25% oder 1/4 zu treffen. Probiere es gerne aus!</p>
    
    
    <?php
    /*
        !!! FUR DIE ANWENDUNG !!!
        die GD-Extension muss im XAMPP server aktiviert sein um das zeichnen des Rades zu unterstutzen
        1. Dateipfad C:\xampp\php\php.ini
        2. In der php.ini datei -> ';extension=gd' auf 'extension=gd' wechseln
        3. (ggf. server starten/neustarten um anderungen zu ubernehmen)
        4. GD-Extension aktiv, bilder koennen gezeichnet werden.
    */

    // rad zeichnen
    function drawWheel($filename, $rotation = 0, $showMotionLines = false) {
        $width = 400;
        $height = 400;
        $centerX = 200;
        $centerY = 200;
        $radius = 150;
        
        $image = imagecreatetruecolor($width, $height);
        
        // farben
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        
        $colors = [
            imagecolorallocate($image, 255, 0, 0),     // Rot
            imagecolorallocate($image, 0, 255, 0),     // Grün
            imagecolorallocate($image, 0, 0, 255),     // Blau
            imagecolorallocate($image, 255, 255, 0)    // Gelb
        ];
        
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // 4 teile
        for ($i = 0; $i < 4; $i++) {
            $startAngle = ($i * 90) + $rotation;
            $endAngle = (($i + 1) * 90) + $rotation;
            
            imagefilledarc($image, $centerX, $centerY, $radius * 2, $radius * 2, 
                          $startAngle, $endAngle, $colors[$i], IMG_ARC_PIE);
        }
        
        // rand
        imageellipse($image, $centerX, $centerY, $radius * 2, $radius * 2, $black);
        
        // bewegung striche
        if ($showMotionLines) {
            imagesetthickness($image, 3);
            for ($i = 0; $i < 8; $i++) {
                $angle = ($i * 45) + $rotation;
                $rad = deg2rad($angle);
                
                // bewegungslinien (quelle ki)
                $x1 = $centerX + cos($rad) * ($radius + 10);
                $y1 = $centerY + sin($rad) * ($radius + 10);
                $x2 = $centerX + cos($rad) * ($radius + 35);
                $y2 = $centerY + sin($rad) * ($radius + 35);
                
                imageline($image, $x1, $y1, $x2, $y2, $black);
            }
            imagesetthickness($image, 1);
        }
        
        // punkt in der mitte
        imagefilledellipse($image, $centerX, $centerY, 20, 20, $black);
        
        // pfeil ganz oben
        $arrowColor = imagecolorallocate($image, 0, 0, 0);
        $arrowPoints = [
            $centerX, 20,           
            $centerX - 15, 50,      
            $centerX + 15, 50       
        ];
        imagefilledpolygon($image, $arrowPoints, 3, $arrowColor);
        
        imagepng($image, $filename);
        imagedestroy($image);
    }
    
    $step = isset($_GET['step']) ? $_GET['step'] : 0;
    
    if (isset($_POST['spin'])) {
        // zufallszahl
        $result = rand(1, 4);
        
        // speichern
        session_start();
        $_SESSION['result'] = $result;
        
        // 22% rotation (bewegung)
        drawWheel('animation.png', 22, true);
        
        // animation weiterleiten
        header('Location: ?step=1');
        exit;
    }
    
    if ($step == 1) {
        session_start();
        $result = $_SESSION['result'];
        
        // rotation = ergebnis
        $rotations = [
            1 => 45,      // Rot
            2 => 135,     // Grün
            3 => 225,     // Blau
            4 => 315      // Gelb
        ];
        
        echo '<h2>Rad dreht sich...</h2>';

        // ergebnis rad keine striche
        drawWheel('ergebnis.png', $rotations[$result], false);
        
        echo '<img src="animation.png?t=' . time() . '" alt="Dreht sich..." width="400" height="400">';
        echo '<meta http-equiv="refresh" content="1;url=?step=2">';
        
    } elseif ($step == 2) {
        // step 2
        session_start();
        $result = $_SESSION['result'];
        $farben = [1 => 'Blau', 2 => 'Grün', 3 => 'Rot', 4 => 'Gelb'];
        
        echo '<h2>Ergebnis: ' . $farben[$result] . '</h2>';
        echo '<img src="ergebnis.png?t=' . time() . '" alt="Ergebnis" width="400" height="400">';
        echo '<br><br>';
        echo '<form method="POST"><button type="submit" name="spin">Nochmal drehen!</button></form>';

    } else {
        echo '<form method="POST"><button type="submit" name="spin">Drehen!</button></form>';
    }
    ?>
    
</body>
</html>