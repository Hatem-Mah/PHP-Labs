<!DOCTYPE html>
<html>

<head>
    <title>Review</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            color: #1f2937;
        }

        .wrapper {
            max-width: 760px;
            margin: 40px auto;
            padding: 0 16px;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 24px;
        }

        .ok {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 12px;
            margin-top: 16px;
        }

        .bad {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 12px;
            margin-top: 16px;
        }

        .actions a {
            text-decoration: none;
            margin-right: 12px;
            color: #2563eb;
        }

        .avatar {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <?php
            require_once 'config/database.php';
            require_once 'config/auth.php';

            renderUserBar();

            $fname = trim($_POST['fname'] ?? '');
            $lname = trim($_POST['lname'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $country = trim($_POST['country'] ?? '');
            $gender = trim($_POST['gender'] ?? '');
            $skills = $_POST['skills'] ?? [];
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $department = trim($_POST['department'] ?? '');
            $code = trim($_POST['code'] ?? '');

            $errors = [];

            if ($fname === '' || !preg_match('/^[A-Za-z]+$/', $fname)) {
                $errors[] = 'First name is required and must contain letters only.';
            }

            if ($lname === '' || !preg_match('/^[A-Za-z]+$/', $lname)) {
                $errors[] = 'Last name is required and must contain letters only.';
            }

            if ($address === '') {
                $errors[] = 'Address is required.';
            }

            if ($country === '' || $country === 'Select Country') {
                $errors[] = 'Country is required.';
            }

            if ($gender !== 'Male' && $gender !== 'Female') {
                $errors[] = 'Gender is required.';
            }

            if (!is_array($skills) || count($skills) === 0) {
                $errors[] = 'At least one skill must be selected.';
            }

            if ($username === '') {
                $errors[] = 'Username is required.';
            }

            if (!preg_match('/^[a-z0-9_]{8}$/', $password)) {
                $errors[] = 'Password must be exactly 8 chars, lowercase letters/numbers, underscore only.';
            }

            if ($code !== 'Sh68Sa') {
                $errors[] = 'Invalid code.';
            }

            $profileImagePath = '';
            if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Profile image is required.';
            } else {
                $file = $_FILES['profile_image'];
                $maxSize = 2 * 1024 * 1024;
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($extension, $allowedExtensions, true)) {
                    $errors[] = 'Profile image must be JPG or PNG.';
                }

                if ((int)$file['size'] > $maxSize) {
                    $errors[] = 'Profile image size must be 2MB or less.';
                }

                if (empty($errors)) {
                    if (!is_dir('uploads')) {
                        mkdir('uploads', 0777, true);
                    }

                    $safeName = uniqid('profile_', true) . '.' . $extension;
                    $targetPath = 'uploads/' . $safeName;

                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        $errors[] = 'Failed to upload profile image.';
                    } else {
                        $profileImagePath = $targetPath;
                    }
                }
            }

            if ($gender === 'Female') {
                $title = 'Ms';
            } else {
                $title = 'Mrs';
            }

            if (!empty($errors)) {
                echo '<h2>Registration Failed</h2>';
                echo '<div class="bad"><ul>';
                foreach ($errors as $validationError) {
                    echo '<li>' . htmlspecialchars($validationError) . '</li>';
                }
                echo '</ul></div>';
                echo '<div class="actions"><a href="registration.html">Back to Registration</a></div>';
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO registrations (fname, lname, address, country, gender, skills, username, password, profile_image, department, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

                    $skillsJson = json_encode($skills);
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $result = $stmt->execute([$fname, $lname, $address, $country, $gender, $skillsJson, $username, $hashedPassword, $profileImagePath, $department, $code]);

                    if ($result) {
                        $insertId = $pdo->lastInsertId();
                        echo '<h2>Review</h2>';
                        echo '<p>Thanks ' . htmlspecialchars($title . ' ' . $fname . ' ' . $lname) . '</p>';
                        echo '<img class="avatar" src="' . htmlspecialchars($profileImagePath) . '" alt="Profile image">';
                        echo '<p><strong>Name:</strong> ' . htmlspecialchars($fname . ' ' . $lname) . '</p>';
                        echo '<p><strong>Address:</strong> ' . htmlspecialchars($address) . '</p>';
                        echo '<p><strong>Your Skills:</strong><br>' . htmlspecialchars(implode(', ', $skills)) . '</p>';
                        echo '<p><strong>Department:</strong> ' . htmlspecialchars($department) . '</p>';
                        echo '<div class="ok"><strong>Data saved successfully!</strong><br><strong>Registration ID:</strong> ' . htmlspecialchars($insertId) . '</div>';
                        echo '<div class="actions"><a href="list_data.php">View All Registrations</a><a href="registration.html">Add New Registration</a></div>';
                    } else {
                        echo '<h2>Registration Failed</h2>';
                        echo '<div class="bad">Failed to save data.</div>';
                        echo '<div class="actions"><a href="registration.html">Try Again</a></div>';
                    }
                } catch (PDOException $e) {
                    echo '<h2>Registration Failed</h2>';
                    echo '<div class="bad">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    echo '<div class="actions"><a href="registration.html">Try Again</a></div>';
                }
            }
            ?>
        </div>
    </div>

</body>

</html>