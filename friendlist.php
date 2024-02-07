<?php
session_start();
require_once("functions/settings.php");
require_once("functions/db_functions.php");


// Check if user is logged in
if (!$_SESSION['logged_in']) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Establish the database connection
$conn = new mysqli($host, $user, $pswd, $dbnm);

if (!$conn) {
    die("<p>Database Connection Unsuccessful</p>");
}


$loggedInUserProfileEmail = $_SESSION['email'];

$loggedInUserProfileName = getProfileNameByEmail($loggedInUserProfileEmail, $conn);

$logged_in_user_id = getFriendIdByEmail($conn, $loggedInUserProfileEmail);
$friends_number = getFriendsNumberById($conn, $logged_in_user_id);

$friends = getFriendsListNameByEmail($loggedInUserProfileEmail, $conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //When the unfriend button is pressed, it will take that friend name and remove it from the list
    $name = $_POST['friend_name'];
    removeFriendByProfileName($logged_in_user_id, $name);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Friend List</title>
</head>

<body>

    <ul>
        <li><a href="index.php">My Friend System</a></li>
        <li><a href="friendadd.php">Add Friend</a></li>
        <li><a href="about.php">About</a></li>
    </ul>

    <h1>My Friend System<br>
        <?php echo "$loggedInUserProfileName's Friend List Page"; ?><br>
        <?php echo "Total number of friend is $friends_number"; ?>

    </h1>

    <h2>Your Friends:</h2>

    <?php
    if ($friends != null) {
        echo "<table>";
        echo "<tr><th>Friend Name</th><th>Action</th></tr>";

        foreach ($friends as $friend) {
            echo "<tr>";
            echo "<td>$friend</td>";
            echo "<td>";
            echo "<form action='friendlist.php' method='post' style='display: inline;'>";
            echo "<input type='hidden' name='friend_name' value='$friend'>";
            echo "<input type='submit' value='Unfriend'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";

    } else {
        echo "$loggedInUserProfileName doesn't have any friend";
    }
    ?>


    <a class="logout" href="logout.php">Log out</a>

    <div class="footer">
        <p>Welcome to My Friend System. Designed by Abrar Hossain Chy Toha</p>
    </div>

</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>