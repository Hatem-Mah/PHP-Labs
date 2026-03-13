<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function currentUserName(): string
{
    $name = $_SESSION['display_name'] ?? ($_SESSION['username'] ?? 'Guest');
    return htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
}

function renderUserBar(): void
{
    echo '<div style="background:#111827;color:#fff;padding:10px 16px;display:flex;justify-content:space-between;align-items:center;">';
    if (isLoggedIn()) {
        echo '<div>Logged in as: <strong>' . currentUserName() . '</strong></div>';
        echo '<div><a style="color:#93c5fd;text-decoration:none;margin-right:12px;" href="home.php">Home</a><a style="color:#93c5fd;text-decoration:none;" href="logout.php">Logout</a></div>';
    } else {
        echo '<div>Not logged in</div>';
        echo '<div><a style="color:#93c5fd;text-decoration:none;" href="login.php">Login</a></div>';
    }
    echo '</div>';
}
