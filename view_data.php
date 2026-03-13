<!DOCTYPE html>
<html>

<head>
    <title>View Registration Details</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            color: #1f2937;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .detail-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            margin: 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #333;
        }

        .detail-value {
            flex: 1;
            color: #666;
        }

        .nav-links {
            margin: 20px 0;
        }

        .nav-links a {
            margin-right: 15px;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .edit-btn {
            background-color: #4CAF50 !important;
        }

        .delete-btn {
            background-color: #f44336 !important;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
        }

        .skill-tag {
            background-color: #e7e7e7;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 15px;
            font-size: 12px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Registration Details</h2>

        <div class="nav-links">
            <a href="list_data.php">← Back to All Registrations</a>
            <a href="registration.html">New Registration</a>
        </div>

        <?php
        require_once 'config/database.php';
        require_once 'config/auth.php';
        requireLogin();
        renderUserBar();

        $id = $_GET['id'] ?? '';

        if (empty($id) || !is_numeric($id)) {
            echo "<div class='error'>Invalid ID provided.</div>";
            exit;
        }

        try {
            
            $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();

            if (!$record) {
                echo "<div class='error'>Record not found.</div>";
                exit;
            }

            echo "<div class='detail-card'>";
            echo "<h3>Registration Information</h3>";
            echo "<img class='avatar' src='" . htmlspecialchars($record['profile_image']) . "' alt='Profile image'>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>ID:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['id']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>First Name:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['fname']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Last Name:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['lname']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Username:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['username']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Address:</div>";
            echo "<div class='detail-value'>" . nl2br(htmlspecialchars($record['address'])) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Country:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['country']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Gender:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['gender']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Skills:</div>";
            echo "<div class='detail-value'>";
            echo "<div class='skills-list'>";
            $skills = json_decode($record['skills'], true) ?: [];
            foreach ($skills as $skill) {
                echo "<span class='skill-tag'>" . htmlspecialchars($skill) . "</span>";
            }
            echo "</div>";
            echo "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Department:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['department']) . "</div>";
            echo "</div>";

            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Registered:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['created_at']) . "</div>";
            echo "</div>";

            if ($record['updated_at'] != $record['created_at']) {
                echo "<div class='detail-row'>";
                echo "<div class='detail-label'>Last Updated:</div>";
                echo "<div class='detail-value'>" . htmlspecialchars($record['updated_at']) . "</div>";
                echo "</div>";
            }

            echo "</div>";

            echo "<div class='nav-links'>";
            echo "<a href='edit_data.php?id=" . urlencode($record['id']) . "' class='edit-btn'>Edit This Record</a>";
            echo "<a href='delete_data.php?id=" . urlencode($record['id']) . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete This Record</a>";
            echo "</div>";
        } catch (PDOException $e) {
            echo "<div class='error'>Database error: " . $e->getMessage() . "</div>";
        }
        ?>

    </div>
</body>

</html>