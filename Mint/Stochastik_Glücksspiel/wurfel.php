<!-- Version 1.02 -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Würfel</title>
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
            background-color: #2196F3;
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
    <h1>Würfel</h1>
    <p class="subtitle">In der folgenden Anwendung würfelst du mit einem Würfel.
    <br></br>Dies ein Laplace Experiment, d.h., dass alle möglichen Ergebnisse die gleiche Wahrscheinlichkeit besitzen.
    <br></br>Jede Zahl hat eine Chance von 16,67% oder 1/6 zu treffen. Probiere es gerne aus!</p>
    
    
    <?php
    /*
        !!! FUR DIE ANWENDUNG !!!
        die GD-Extension muss im XAMPP server aktiviert sein um das zeichnen des Würfels zu unterstützen
        1. Dateipfad C:\xampp\php\php.ini
        2. In der php.ini datei -> ';extension=gd' auf 'extension=gd' wechseln
        3. (ggf. server starten/neustarten um änderungen zu übernehmen)
        4. GD-Extension aktiv, bilder können gezeichnet werden.
    */

    // würfel zeichnen
    function drawDice($filename, $number, $isAnimating = false) {
        $width = 300;
        $height = 300;
        
        $image = imagecreatetruecolor($width, $height);
        
        // farben
        $white = imagecolorallocate($image, 255, 255, 255);
        $red = imagecolorallocate($image, 255, 0, 0);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        // würfel zeichnen (weißes quadrat mit schwarzem rand)
        $diceSize = 200;
        $diceX = ($width - $diceSize) / 2;
        $diceY = ($height - $diceSize) / 2;
        
        // weißer würfel
        imagefilledrectangle($image, $diceX, $diceY, $diceX + $diceSize, $diceY + $diceSize, $white);
        
        // schwarzer rand
        imagesetthickness($image, 4);
        imagerectangle($image, $diceX, $diceY, $diceX + $diceSize, $diceY + $diceSize, $black);
        
        // punkte nur zeichnen wenn nicht animiert
        if (!$isAnimating) {
            $dotRadius = 15;
            $centerX = $diceX + $diceSize / 2;
            $centerY = $diceY + $diceSize / 2;
            $spacing = 50;
            
            // positionen für punkte
            $positions = [
                'center' => [$centerX, $centerY],
                'topLeft' => [$centerX - $spacing, $centerY - $spacing],
                'topRight' => [$centerX + $spacing, $centerY - $spacing],
                'middleLeft' => [$centerX - $spacing, $centerY],
                'middleRight' => [$centerX + $spacing, $centerY],
                'bottomLeft' => [$centerX - $spacing, $centerY + $spacing],
                'bottomRight' => [$centerX + $spacing, $centerY + $spacing],
            ];
            
            // zeichne punkte je nach zahl
            switch ($number) {
            case 1:
                imagefilledellipse($image, $positions['center'][0], $positions['center'][1], $dotRadius * 2, $dotRadius * 2, $red);
                break;
            case 2:
                imagefilledellipse($image, $positions['topLeft'][0], $positions['topLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomRight'][0], $positions['bottomRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                break;
            case 3:
                imagefilledellipse($image, $positions['topLeft'][0], $positions['topLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['center'][0], $positions['center'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomRight'][0], $positions['bottomRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                break;
            case 4:
                imagefilledellipse($image, $positions['topLeft'][0], $positions['topLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['topRight'][0], $positions['topRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomLeft'][0], $positions['bottomLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomRight'][0], $positions['bottomRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                break;
            case 5:
                imagefilledellipse($image, $positions['topLeft'][0], $positions['topLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['topRight'][0], $positions['topRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['center'][0], $positions['center'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomLeft'][0], $positions['bottomLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomRight'][0], $positions['bottomRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                break;
            case 6:
                imagefilledellipse($image, $positions['topLeft'][0], $positions['topLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['topRight'][0], $positions['topRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['middleLeft'][0], $positions['middleLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['middleRight'][0], $positions['middleRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomLeft'][0], $positions['bottomLeft'][1], $dotRadius * 2, $dotRadius * 2, $red);
                imagefilledellipse($image, $positions['bottomRight'][0], $positions['bottomRight'][1], $dotRadius * 2, $dotRadius * 2, $red);
                break;
        }
        }
        
        imagepng($image, $filename);
        imagedestroy($image);
    }
    
    $step = isset($_GET['step']) ? $_GET['step'] : 0;
    
    if (isset($_POST['roll'])) {
        // zufallszahl 1-6
        $result = rand(1, 6);
        
        // speichern
        session_start();
        $_SESSION['wuerfel_result'] = $result;
        
        // animations-würfel mit bewegungslinien
        drawDice('wuerfel_animation.png', rand(1, 6), true);
        
        // animation weiterleiten
        header('Location: ?step=1');
        exit;
    }
    
    if ($step == 1) {
        session_start();
        $result = $_SESSION['wuerfel_result'];
        
        echo '<h2>Würfel rollt...</h2>';

        // ergebnis würfel ohne bewegungslinien
        drawDice('wuerfel_ergebnis.png', $result, false);
        
        echo '<img src="wuerfel_animation.png?t=' . time() . '" alt="Rollt..." width="300" height="300">';
        echo '<meta http-equiv="refresh" content="1;url=?step=2">';
        
    } elseif ($step == 2) {
        // step 2
        session_start();
        $result = $_SESSION['wuerfel_result'];
        
        echo '<h2>Ergebnis: ' . $result . '</h2>';
        echo '<img src="wuerfel_ergebnis.png?t=' . time() . '" alt="Ergebnis" width="300" height="300">';
        echo '<br><br>';
        echo '<form method="POST"><button type="submit" name="roll">Nochmal würfeln!</button></form>';

    } else {
        echo '<form method="POST"><button type="submit" name="roll">Würfeln!</button></form>';
    }
    ?>
    
</body>
</html>