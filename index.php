<?php
require_once("functions/settings.php");
require_once("data.php");

$conn = new mysqli($host, $user, $pswd, $dbnm);

if (!$conn) {
    die("<p>Database Connection Unsuccessful</p>");
}

$result1 = CheckFriendsTable($conn);
if (!$result1) { //If table doesn't exist, create table and insert data
    CreateTableFriends($conn);
    InsertDataFriends($conn);
}
$result2 = CheckMyFriendsTable($conn);
if (!$result2) { //If table doesn't exist, create table and insert data
    CreateTableMyFriends($conn);
    InsertDataMyFriends($conn);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Web application development">
    <meta name="keywords" content="PHP">
    <meta name="author" content="Abrar Hossain Chy Toha">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <ul>
        <li><a href="index.php">My Friend System</a></li>
        <li><a href="signup.php">Create New Account</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="about.php">About</a></li>
    </ul>

    <h1>My Friend System</h1>
    <h1>Assignment Home Page</h1>
    <pre>Name: Abrar Hossain Chy Toha               Student ID: 103506608</pre>
    <p>Email: 103506608@student.swin.edu.au</p>
    <p>I declare that this assignment is my individual work. I have not worked collaboratively
        nor have I copied from any other student’s work or from any other source.</p>
    <?php
    if (!$result1 && !$result2) { //Delete all the tables to check this functionality
        echo "<p>Table created succesfully and data inserted</p>";
    }
    ?>
    <div class="footer">
        <p>Welcome to My Friend System. Designed by Abrar Hossain Chy Toha</p>
    </div>

</body>

</html>