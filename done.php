<!DOCTYPE html>
<html>

<head>
    <title>Review</title>
</head>

<body>

    <?php

    // Get data from GET
    $fname = $_GET['fname'];
    $lname = $_GET['lname'];
    $address = $_GET['address'];
    $gender = $_GET['gender'];
    $skills = $_GET['skills'];
    $department = $_GET['department'];

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

</body>

</html>