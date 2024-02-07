<?php
session_start();
require_once("functions/settings.php");
require_once("functions/db_functions.php");

// Check if user is logged in
if (!$_SESSION['logged_in']) {
    header("Location: login.php"); // Redirect to index page if not logged in
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
$not_friends = getUnfriendListNameById($conn, $logged_in_user_id);

// Pagination settings
$items_per_page = 5;
$total_pages = ceil(count($not_friends) / $items_per_page);
$current_page = isset($_GET['page']) && $_GET['page'] >= 1 && $_GET['page'] <= $total_pages ? $_GET['page'] : 1;
$start_index = ($current_page - 1) * $items_per_page;
$displayed_friends = array_slice($not_friends, $start_index, $items_per_page);

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //if Add as Friend button is pressed, it will come here and insert data in myfriends table
    $name = $_POST['friend_name'];
    AddFriendByName($name, $logged_in_user_id);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Friends</title>
</head>

<body>

    <ul>
        <li><a href="index.php">My Friend System</a></li>
        <li><a href="friendlist.php">Friend List</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
    <h1>My Friend System<br>
        <?php echo "$loggedInUserProfileName's Add Friend Page"; ?><br>
        <?php echo "Total number of friends: $friends_number"; ?>

    </h1>


    <table>
        <thead>
            <tr>
                <th>Profile Name</th>
                <th>Mutual Friends</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($displayed_friends != null) {
                foreach ($displayed_friends as $not_friend) {
                    $mutualFriendCount = getMutualFriendCount($conn, $logged_in_user_id, $not_friend);
                    echo "<tr>";
                    echo "<td>$not_friend</td>";
                    echo "<td>$mutualFriendCount</td>";
                    echo "<td>";
                    echo "<form action='friendadd.php' method='post' style='display: inline;'>";
                    echo "<input type='hidden' name='friend_name' value='$not_friend'>";
                    echo "<input type='submit' value='Add as Friend'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='3'>There are no users to make friends</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>


    <!-- Pagination -->
    <div class="prevoius-next">
        <?php if ($total_pages > 1): ?>
            <?php if ($current_page > 1): ?>
                <a class="logout " href="?page=<?php echo $current_page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php if ($current_page < $total_pages): ?>
                <a class="logout" href="?page=<?php echo $current_page + 1; ?>">Next</a>
            <?php endif; ?>
        <?php endif; ?>

    </div>

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