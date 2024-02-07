<?php
session_start();
require_once("functions/settings.php");
require_once("functions/validation_functions.php");
require_once("functions/db_functions.php");

if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { //if already logged in, it will ridirect to the friendadd page
    header("Location: friendadd.php");
    exit();
}

// Establish the database connection
$conn = new mysqli($host, $user, $pswd, $dbnm);

if (!$conn) {
    die("<p>Database Connection Unsuccessful</p>");
}

$errors = array();



// Check if the form was submitted and SignUp button was pressed
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["signup"]) && $_POST["signup"] == "SignUp") {
    // Get form data
    $email = $_POST['email'];
    $profile_name = $_POST['profile_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email input data
    if (empty($email)) {
        $errors[] = "Email can't be empty";
    } else {
        if (!isValidEmail($email)) { //generate this error if email is not in correct format
            $errors[] = "Invalid email format. Please try again.";
        } else {
            // Call emailMatch to check if the email already exists or not
            $result = emailMatch($email, $conn);
            if ($result) {
                $errors[] = "Email already used for an account";

            }
        }
    }

    if (empty($profile_name)) { //validate profile name input data
        $errors[] = "Profile name can't be empty";
    } else {
        if (!isValidProfileName($profile_name)) {
            $errors[] = "Profile name must contain only letters. Please try again.";
        }
    }
    if (empty($password)) { //validate password input data
        $errors[] = "Password can't be empty";
    } else {
        if (!isValidPassword($password)) {
            $errors[] = "Invalid password. Passwords must contain only letters and numbers. Please try again.";
        } else {
            if ($password !== $confirm_password) { //if both password doesn't match, it will generate this
                $errors[] = "Both passwords must match";
            }
        }
    }




    if (count($errors) == 0) {
        // Prepare the query to insert data into the friends table as new user
        $query = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends) VALUES ('$email', '$password', '$profile_name', now(), 0)";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email; //set the variable for friendadd page to login
            // Redirect to friendadd.php
            header("Location: friendadd.php");
            exit();
        } else {
            $errors[] = "Error inserting data into the database: " . mysqli_error($conn);
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Registration</title>
</head>

<body>

    <ul>
        <li><a href="index.php">My Friend System</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="about.php">About</a></li>
    </ul>

    <h1>My Friend System
        Registration Page</h1>

    <?php

    // Display errors
    if (!empty($errors)) {

        foreach ($errors as $error) {
            echo $error . "<br>";
        }

    }
    ?>

    <form class="form-login-signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email Address:</label>
        <input type="text" id="email" name="email"
            value="<?php echo !empty($email) ? htmlspecialchars($email) : ''; ?>"><br>
        <!-- this keeps the value of email, if there is anything wrong -->

        <label for="profile_name">Profile Name:</label>
        <input type="text" id="profile_name" name="profile_name"
            value="<?php echo !empty($profile_name) ? htmlspecialchars($profile_name) : ''; ?>"><br>
        <!-- this keeps the value of name, if there is anything wrong -->


        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br>
        <div class="button-container">
            <input type="submit" name="signup" value="SignUp">
            <input type="submit" name="reset" value="Reset">
        </div>
    </form>


    <div class="footer">
        <p>Welcome to My Friend System. Designed by Abrar Hossain Chy Toha</p>
    </div>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>