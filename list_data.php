<!DOCTYPE html>
<html>

<head>
    <title>All Registrations</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            color: #1f2937;
        }

        .container {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .panel {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background: #fff;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            text-align: center;
        }

        .actions a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }

        .view {
            background-color: #2196F3;
        }

        .edit {
            background-color: #4CAF50;
        }

        .delete {
            background-color: #f44336;
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

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d1d5db;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="panel">
            <h2>All Registrations</h2>

            <div class="nav-links">
                <a href="registration.html">New Registration</a>
            </div>

    <?php
    require_once 'autoload.php';
    $auth = Auth::getInstance();
    $auth->requireLogin();
    $auth->renderUserBar();

    try {
        $repo = new Registration();
        $data = $repo->getAll();

        if (empty($data)) {
            echo "<p>No registrations yet. <a href='registration.html'>Add a new registration</a></p>";
        } else {
            echo "<p>Total Registrations: " . count($data) . "</p>";
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Photo</th>";
            echo "<th>Name</th>";
            echo "<th>Email/Username</th>";
            echo "<th>Address</th>";
            echo "<th>Country</th>";
            echo "<th>Gender</th>";
            echo "<th>Skills</th>";
            echo "<th>Department</th>";
            echo "<th>Registered</th>";
            echo "<th>Actions</th>";
            echo "</tr>";

            foreach ($data as $record) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($record['id']) . "</td>";
                echo "<td><img class='avatar' src='" . htmlspecialchars($record['profile_image']) . "' alt='Profile'></td>";
                echo "<td>" . htmlspecialchars($record['fname'] . ' ' . $record['lname']) . "</td>";
                echo "<td>" . htmlspecialchars($record['username']) . "</td>";
                echo "<td>" . htmlspecialchars($record['address']) . "</td>";
                echo "<td>" . htmlspecialchars($record['country']) . "</td>";
                echo "<td>" . htmlspecialchars($record['gender']) . "</td>";

                $skills = json_decode($record['skills'], true);
                echo "<td>" . htmlspecialchars(implode(', ', $skills)) . "</td>";
                echo "<td>" . htmlspecialchars($record['department']) . "</td>";
                echo "<td>" . htmlspecialchars($record['created_at']) . "</td>";
                echo "<td class='actions'>";
                echo "<a href='view_data.php?id=" . urlencode($record['id']) . "' class='view'>View</a>";
                echo "<a href='edit_data.php?id=" . urlencode($record['id']) . "' class='edit'>Edit</a>";
                echo "<a href='delete_data.php?id=" . urlencode($record['id']) . "' class='delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    } catch (PDOException $e) {
        echo "<p>Database error: " . $e->getMessage() . "</p>";
        echo "<p>Make sure XAMPP MySQL is running and database is created.</p>";
    }
    ?>
        </div>
    </div>
</body>

</html>