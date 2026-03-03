<!DOCTYPE html>
<html>

<head>
    <title>Review</title>
</head>

<body>

    <?php

    // Get data from POST
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

    // Store data in a file
    $data = [
        'id' => uniqid(),
        'timestamp' => date('Y-m-d H:i:s'),
        'fname' => $fname,
        'lname' => $lname,
        'address' => $address,
        'country' => $country,
        'gender' => $gender,
        'skills' => $skills,
        'username' => $username,
        'password' => $password,
        'department' => $department,
        'code' => $code
    ];

    $file = 'data.json';
    $currentData = [];
    
    // Read existing data if file exists
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        $currentData = json_decode($jsonData, true) ?: [];
    }
    
    // Add new data
    $currentData[] = $data;
    
    // Save back to file
    file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));

    // Title based on gender
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

    <p>
        <strong>Data saved successfully!</strong><br>
        <a href="list_data.php">View All Registrations</a>
    </p>

</body>

</html>