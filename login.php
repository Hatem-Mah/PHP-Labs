<?php
require_once 'config/database.php';
require_once 'config/auth.php';

if (isLoggedIn()) {
    header('Location: home.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT id, fname, lname, username, password FROM registrations WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['display_name'] = $user['fname'] . ' ' . $user['lname'];

            header('Location: home.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f2f4f8; color: #1f2937; }
        .box { max-width: 420px; margin: 60px auto; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); padding: 24px; }
        h1 { margin-top: 0; }
        .field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
        input { border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 14px; }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 8px; padding: 10px; margin-bottom: 14px; }
        button { border: none; border-radius: 8px; padding: 10px 16px; background: #2563eb; color: #fff; cursor: pointer; }
        a { color: #2563eb; text-decoration: none; }
    </style>
</head>

<body>
    <?php renderUserBar(); ?>
    <div class="box">
        <h1>Login</h1>
        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password">
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="registration.html">Register</a></p>
    </div>
</body>

</html>
