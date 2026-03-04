<!DOCTYPE html>
<html>

<head>
    <title>Edit Registration</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .form-group {
            margin: 15px 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .checkbox-group,
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 5px;
        }

        .checkbox-group label,
        .radio-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
            margin-bottom: 0;
        }

        .checkbox-group input,
        .radio-group input {
            width: auto;
            margin-right: 5px;
        }

        .nav-links {
            margin: 20px 0;
        }

        .nav-links a,
        .btn {
            margin-right: 15px;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .back-btn {
            background-color: #6c757d;
        }

        .update-btn {
            background-color: #28a745;
        }

        .cancel-btn {
            background-color: #dc3545;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
    <script>
        function validateForm() {
            let fname = document.forms["editForm"]["fname"].value.trim();
            let lname = document.forms["editForm"]["lname"].value.trim();
            let address = document.forms["editForm"]["address"].value.trim();
            let country = document.forms["editForm"]["country"].value;
            let gender = document.querySelector('input[name="gender"]:checked');
            let skills = document.querySelectorAll('input[name="skills[]"]:checked');
            let username = document.forms["editForm"]["username"].value.trim();

            let namePattern = /^[A-Za-z]+$/;

            if (fname === "" || !namePattern.test(fname)) {
                alert("Invalid First Name");
                return false;
            }

            if (lname === "" || !namePattern.test(lname)) {
                alert("Invalid Last Name");
                return false;
            }

            if (address === "") {
                alert("Address required");
                return false;
            }

            if (country === "Select Country") {
                alert("Select country");
                return false;
            }

            if (!gender) {
                alert("Select gender");
                return false;
            }

            if (skills.length === 0) {
                alert("Select at least one skill");
                return false;
            }

            if (username.length < 4) {
                alert("Username too short");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>Edit Registration</h2>

        <?php
        require_once 'config/database.php';

        $id = $_GET['id'] ?? $_POST['id'] ?? '';
        $isUpdate = $_POST['update'] ?? false;

        if (empty($id) || !is_numeric($id)) {
            echo "<div class='error'>Invalid ID provided.</div>";
            echo "<div class='nav-links'>";
            echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
            echo "</div>";
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();

            if (!$record) {
                echo "<div class='error'>Record not found.</div>";
                echo "<div class='nav-links'>";
                echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
                echo "</div>";
                exit;
            }

            if ($isUpdate) {
                $updateStmt = $pdo->prepare("UPDATE registrations SET fname = ?, lname = ?, address = ?, country = ?, gender = ?, skills = ?, username = ?, department = ? WHERE id = ?");
                $skillsJson = json_encode($_POST['skills'] ?? []);

                if ($updateStmt->execute([
                    $_POST['fname'],
                    $_POST['lname'],
                    $_POST['address'],
                    $_POST['country'],
                    $_POST['gender'],
                    $skillsJson,
                    $_POST['username'],
                    $_POST['department'],
                    $id
                ])) {
                    echo "<div class='success'>";
                    echo "<h3>✓ Record Updated Successfully</h3>";
                    echo "<p>The registration has been updated in the database.</p>";
                    echo "</div>";

                    echo "<div class='nav-links'>";
                    echo "<a href='view_data.php?id=" . urlencode($id) . "' class='update-btn'>View Updated Record</a>";
                    echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
                    echo "</div>";

                    $stmt->execute([$id]);
                    $record = $stmt->fetch();
                } else {
                    echo "<div class='error'>";
                    echo "<h3>✗ Error</h3>";
                    echo "<p>Failed to update the record. Please try again.</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='nav-links'>";
                echo "<a href='view_data.php?id=" . urlencode($record['id']) . "' class='back-btn'>← Cancel & View Record</a>";
                echo "<a href='list_data.php' class='back-btn'>← Back to List</a>";
                echo "</div>";

                echo "<div class='form-card'>";
                echo "<form name='editForm' method='POST' onsubmit='return validateForm()'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>";
                echo "<input type='hidden' name='update' value='1'>";

                echo "<div class='form-group'>";
                echo "<label for='fname'>First Name:</label>";
                echo "<input type='text' name='fname' id='fname' value='" . htmlspecialchars($record['fname']) . "' required>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label for='lname'>Last Name:</label>";
                echo "<input type='text' name='lname' id='lname' value='" . htmlspecialchars($record['lname']) . "' required>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label for='address'>Address:</label>";
                echo "<textarea name='address' id='address' required>" . htmlspecialchars($record['address']) . "</textarea>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label for='country'>Country:</label>";
                echo "<select name='country' id='country' required>";
                $countries = ['Select Country', 'Egypt', 'USA', 'UK'];
                foreach ($countries as $country) {
                    $selected = ($record['country'] === $country) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($country) . "' $selected>" . htmlspecialchars($country) . "</option>";
                }
                echo "</select>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label>Gender:</label>";
                echo "<div class='radio-group'>";
                $maleChecked = ($record['gender'] === 'Male') ? 'checked' : '';
                $femaleChecked = ($record['gender'] === 'Female') ? 'checked' : '';
                echo "<label><input type='radio' name='gender' value='Male' $maleChecked> Male</label>";
                echo "<label><input type='radio' name='gender' value='Female' $femaleChecked> Female</label>";
                echo "</div>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label>Skills:</label>";
                echo "<div class='checkbox-group'>";
                $allSkills = ['PHP', 'MySQL', 'J2SE', 'PostgreSQL'];
                $recordSkills = json_decode($record['skills'], true) ?: [];
                foreach ($allSkills as $skill) {
                    $checked = in_array($skill, $recordSkills) ? 'checked' : '';
                    echo "<label><input type='checkbox' name='skills[]' value='" . htmlspecialchars($skill) . "' $checked> " . htmlspecialchars($skill) . "</label>";
                }
                echo "</div>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' name='username' id='username' value='" . htmlspecialchars($record['username']) . "' required>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label for='department'>Department:</label>";
                echo "<input type='text' name='department' id='department' value='" . htmlspecialchars($record['department']) . "' readonly>";
                echo "</div>";

                echo "<div class='nav-links'>";
                echo "<button type='submit' class='btn update-btn'>Update Record</button>";
                echo "<a href='view_data.php?id=" . urlencode($record['id']) . "' class='cancel-btn'>Cancel</a>";
                echo "</div>";

                echo "</form>";
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='error'>Database error: " . $e->getMessage() . "</div>";
            echo "<div class='nav-links'>";
            echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
            echo "</div>";
        }
        ?>

    </div>
</body>

</html>