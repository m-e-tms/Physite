<?php
// ============================================================
//  db.php  –  Datenbankverbindung & Einrichtung
//  Passe die Zugangsdaten unten an deine MySQL-Installation an.
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // <-- Benutzername anpassen
define('DB_PASS', '');              // <-- Passwort anpassen
define('DB_NAME', 'mint_db');

function getDB(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($conn->connect_error) {
            die('Datenbankverbindung fehlgeschlagen: ' . $conn->connect_error);
        }
        $conn->set_charset('utf8mb4');

        // Datenbank anlegen falls nicht vorhanden
        $conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->select_db(DB_NAME);

        // Tabelle: Benutzer
        $conn->query("
            CREATE TABLE IF NOT EXISTS `users` (
                `id`              INT AUTO_INCREMENT PRIMARY KEY,
                `name_part1`      VARCHAR(50)  NOT NULL,
                `name_part2`      VARCHAR(50)  NOT NULL,
                `name_number`     VARCHAR(20)  NOT NULL,
                `username`        VARCHAR(130) NOT NULL UNIQUE,
                `password_hash`   VARCHAR(255) NOT NULL,
                `score_kopfrechnen_zeit`  INT DEFAULT 0,
                `score_kopfrechnen_level` INT DEFAULT 0,
                `score_raetsel`           INT DEFAULT 0,
                `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB
        ");
    }
    return $conn;
}
?>
