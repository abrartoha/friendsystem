<?php
session_start();
require_once("functions/settings.php");
require_once("functions/validation_functions.php");
require_once("functions/db_functions.php");
if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { //if already logged in, ridirect to friendlist page
    header("Location: friendlist.php");
    exit();
}


$conn = new mysqli($host, $user, $pswd, $dbnm);

if (!$conn) { //set the database connection
    die("<p>Database Connection Unsuccessful</p>");
}

$errors = array();


if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["login"]) && $_POST["login"] == "Login") {
    $email = $_POST['email']; //if login button is pressed, it will check this condition and set the value for email and password
    $password = $_POST['password'];

    if (empty($email) || empty($password)) { //if any of these are empty, it will store error
        $errors[] = "Both email and password are required.";
    } else {
        if (!isValidEmail($email)) { //if email is wrong format, it will store this error
            $errors[] = "Invalid email format. Please try again.";
        } else {
            if (!isValidPassword($password)) { //if password is in wrong format, it will store this error
                $errors[] = "Invalid password format. Please try again.";
            } else {
                if (!EmailPasswordMatch($email, $password, $conn)) { //if both email and password don't match, it will store this error
                    $errors[] = "No user found with given credentials. Try again with valid email and password. If new user, sign up now. ";
                } else {

                    if (empty($errors)) { //if there is no error, it will set a successful logged in status as session variable
                        $_SESSION['logged_in'] = true;
                        $_SESSION['email'] = $email;

                        header("Location: friendlist.php"); //And ridirect to friendlist page
                        exit();
                    }
                }
            }
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
    <title>User Login</title>
</head>

<body>

    <ul>
        <li><a href="index.php">My Friend System</a></li>
        <li><a href="signup.php">Create New Account</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
    <h1>My Friend System Login Page</h1>

    <?php
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

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>

        <div class="button-container">
            <input type="submit" name="login" value="Login">
            <input type="submit" name="reset" value="Reset">
        </div>

    </form>
    <div class="footer">
        <p>Welcome to My Friend System. Designed by Abrar Hossain Chy Toha</p>
    </div>

</body>

</html>

<?php
mysqli_close($conn); //close the connection
?>