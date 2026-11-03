<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function hub_account_bar(): void {
    $username = $_SESSION['username'] ?? null;
    echo '<div class="hub-auth">';
    if ($username) {
        echo '<span class="user-badge">' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '</span>';
        echo '<a class="logout-btn" href="../db_backend/logout.php">Abmelden</a>';
    } else {
        echo '<a class="logout-btn" href="../Auswahlseite/index.php">Anmelden</a>';
    }
    echo '</div>';
}
