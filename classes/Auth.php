<?php

class Auth
{
    private static ?Auth $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = Database::getInstance()->getConnection();
    }

    public static function getInstance(): Auth
    {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }

    public function currentUserName(): string
    {
        $name = $_SESSION['display_name'] ?? ($_SESSION['username'] ?? 'Guest');
        return htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    }

    public function login(string $username, string $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM registrations WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['display_name'] = $user['fname'] . ' ' . $user['lname'];
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    public function renderUserBar(): void
    {
        echo '<div style="background:#111827;color:#fff;padding:10px 16px;display:flex;justify-content:space-between;align-items:center;">';
        if ($this->isLoggedIn()) {
            echo '<div>Logged in as: <strong>' . $this->currentUserName() . '</strong></div>';
            echo '<div><a style="color:#93c5fd;text-decoration:none;margin-right:12px;" href="home.php">Home</a><a style="color:#93c5fd;text-decoration:none;" href="logout.php">Logout</a></div>';
        } else {
            echo '<div>Not logged in</div>';
            echo '<div><a style="color:#93c5fd;text-decoration:none;" href="login.php">Login</a></div>';
        }
        echo '</div>';
    }
}
