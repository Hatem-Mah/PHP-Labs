<?php
require_once 'config/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f2f4f8; color: #1f2937; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); padding: 24px; }
        .links a { display: inline-block; margin: 8px 10px 0 0; background: #2563eb; color: #fff; text-decoration: none; padding: 10px 14px; border-radius: 8px; }
    </style>
</head>

<body>
    <?php renderUserBar(); ?>
    <div class="container">
        <div class="card">
            <h1>Welcome, <?php echo currentUserName(); ?></h1>
            <p>You are logged in successfully.</p>
            <div class="links">
                <a href="list_data.php">View Registrations</a>
                <a href="registration.html">Create New Registration</a>
            </div>
        </div>
    </div>
</body>

</html>
