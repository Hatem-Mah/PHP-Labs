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
            require_once 'autoload.php';
            $auth = Auth::getInstance();
            $auth->renderUserBar();

            $fname      = trim($_POST['fname']      ?? '');
            $lname      = trim($_POST['lname']      ?? '');
            $address    = trim($_POST['address']    ?? '');
            $country    = trim($_POST['country']    ?? '');
            $gender     = trim($_POST['gender']     ?? '');
            $skills     = $_POST['skills']          ?? [];
            $username   = trim($_POST['username']   ?? '');
            $password   = trim($_POST['password']   ?? '');
            $department = trim($_POST['department'] ?? '');
            $code       = trim($_POST['code']       ?? '');

            $validator = new Validator();
            $validator->requireName($fname, 'First name');
            $validator->requireName($lname, 'Last name');
            $validator->requireNotEmpty($address, 'Address');
            $validator->requireCountry($country);
            $validator->requireGender($gender);
            $validator->requireSkills(is_array($skills) ? $skills : []);
            $validator->requireNotEmpty($username, 'Username');
            $validator->requirePassword($password);
            $validator->requireCode($code, 'Sh68Sa');

            $upload = new FileUpload();
            $profileImagePath = $upload->handle($_FILES['profile_image'] ?? []);
            if ($profileImagePath === null) {
                $validator->addError($upload->getError());
            }

            if ($validator->hasErrors()) {
                echo '<h2>Registration Failed</h2>';
                echo '<div class="bad"><ul>';
                foreach ($validator->getErrors() as $err) {
                    echo '<li>' . htmlspecialchars($err) . '</li>';
                }
                echo '</ul></div>';
                echo '<div class="actions"><a href="registration.html">Back to Registration</a></div>';
            } else {
                try {
                    $title = ($gender === 'Female') ? 'Ms' : 'Mr';
                    $repo  = new Registration();
                    $saved = $repo->create(
                        compact('fname', 'lname', 'address', 'country', 'gender', 'skills', 'username', 'password', 'department', 'code'),
                        $profileImagePath
                    );

                    if ($saved) {
                        $insertId = Database::getInstance()->getConnection()->lastInsertId();
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