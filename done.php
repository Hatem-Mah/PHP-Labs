<!DOCTYPE html>
<html>

<head>
    <title>Review</title>
</head>

<body>

    <?php
    require_once 'config/database.php';

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $gender = $_POST['gender'];
    $skills = $_POST['skills'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $code = $_POST['code'];

    try {
        $stmt = $pdo->prepare("INSERT INTO registrations (fname, lname, address, country, gender, skills, username, password, department, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $skillsJson = json_encode($skills);
        

        if ($result) {
            $success = true;
            $insertId = $pdo->lastInsertId();
        } else {
            $success = false;
            $error = "Failed to save data";
        }
    } catch (PDOException $e) {
        $success = false;
        $error = "Database error: " . $e->getMessage();
    }
    if ($gender == "Female") {
        $title = "Ms";
    } else {
        $title = "Mrs";
    }

    ?>

    <h2>Review</h2>

    <p>
        Thanks <?php echo $title . " " . $fname . " " . $lname; ?>
    </p>

    <p>Please Review Your Information:</p>

    <p>
        <strong>Name:</strong>
        <?php echo $fname . " " . $lname; ?>
    </p>

    <p>
        <strong>Address:</strong>
        <?php echo $address; ?>
    </p>

    <p>
        <strong>Your Skills:</strong><br>
        <?php
        foreach ($skills as $skill) {
            echo $skill . "<br>";
        }
        ?>
    </p>

    <p>
        <strong>Department:</strong>
        <?php echo $department; ?>
    </p>

    <?php if (isset($success) && $success): ?>
        <p>
            <strong>Data saved successfully to database!</strong><br>
            <strong>Registration ID:</strong> <?php echo $insertId; ?><br>
            <a href="list_data.php">View All Registrations</a>
        </p>
    <?php else: ?>
        <p>
            <strong>Error occurred:</strong><br>
            <?php echo isset($error) ? $error : "Unknown error"; ?><br>
            <a href="registration.html">Try Again</a>
        </p>
    <?php endif; ?>

</body>

</html>