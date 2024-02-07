<?php



function emailMatch($email, $conn) //Match email with the given one if exist any for signup
{
    $queryString = "SELECT friend_email FROM friends WHERE friend_email = '$email'";
    $result = mysqli_query($conn, $queryString);

    if (mysqli_num_rows($result) > 0) {
        mysqli_free_result($result);
        return true;
    } else {
        return null;
    }

}

function EmailPasswordMatch($email, $password, $conn) //Both email and password check for login
{
    $queryString = "SELECT * FROM friends WHERE friend_email = '$email' and password = '$password'";
    $result = mysqli_query($conn, $queryString);

    if (mysqli_num_rows($result) > 0) {
        mysqli_free_result($result);
        return true;
    } else {
        return null;
    }
}


function getProfileNameByEmail($email, $conn) //Return profile_name by using email
{
    $query = "SELECT profile_name FROM friends WHERE friend_email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['profile_name'];
        mysqli_free_result($result);
        return $name;
    } else {
        return null;
    }
}



function getFriendsListNameByEmail($email, $conn) // Return friend lists of a profile by using email
{
    $query = "SELECT f.profile_name
              FROM myfriends AS mf
              JOIN friends AS f ON mf.friend_id2 = f.friend_id
              WHERE mf.friend_id1 IN (
                  SELECT friend_id
                  FROM friends
                  WHERE friend_email = '$email'
              )
              ORDER BY f.profile_name;";

    $result = mysqli_query($conn, $query);

    $friends = array();

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $friends[] = $row['profile_name'];
        }
        mysqli_free_result($result);
        return $friends;
    } else {
        return null;
    }
}




require_once("settings.php");
$conn = new mysqli($host, $user, $pswd, $dbnm);
if (!$conn) {
    die("<p>Database Connection Unsuccessful</p>");
}
function removeFriendByProfileName($userid, $friend) //Remove friend by using profile name for unfriend functionality
{
    global $conn;

    $id = getFriendIdByName($conn, $friend);

    $query = "DELETE FROM myfriends 
    WHERE friend_id1 = $userid
    AND friend_id2 = $id;";


    global $conn;
    if (mysqli_query($conn, $query)) {
        // Redirect to refresh the friend list
        header("Location: friendlist.php");
        exit();
    } else {
        echo "Error removing friend: " . mysqli_error($conn);
    }
}



function getFriendIdByEmail($conn, $email) //Get friend_id by using email
{
    $string = "SELECT friend_id
    FROM friends
    WHERE friend_email = '$email';";
    $result = mysqli_query($conn, $string);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['friend_id'];
        }
        mysqli_free_result($result);
        return $id;
    } else {
        return null;
    }

}

function getFriendIdByName($conn, $name) //Get friend_id by using the name of a user
{
    $string = "SELECT friend_id
    from friends
    WHERE profile_name = '$name';";
    $result = mysqli_query($conn, $string);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['friend_id'];
        }
        mysqli_free_result($result);
        return $id;
    } else {
        return null;
    }

}

function getUnfriendListNameById($conn, $id) //Get unfriend list by using id of a user
{
    $string = "SELECT profile_name
    FROM friends
    WHERE friend_id NOT IN (
        SELECT friend_id2
        FROM myfriends
        WHERE friend_id1 = $id
    )
    AND friend_id != $id
    ORDER BY profile_name;
    ";
    $names = array();
    $result = mysqli_query($conn, $string);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $names[] = $row['profile_name'];
        }
        mysqli_free_result($result);
        return $names;
    } else {
        return null;
    }
}

function AddFriendByName($name, $id1) //Add friend by using its name
{
    global $conn;
    $id = getFriendIdByName($conn, $name);
    $string = "INSERT INTO myfriends (friend_id1, friend_id2)
                VALUES ($id1, $id);";
    $result = mysqli_query($conn, $string);
    if ($result) {
        header("Location: friendadd.php");
        exit();
    } else {
        echo "Error adding friend: " . mysqli_error($conn);
    }
}

function getFriendsNumberById($conn, $id) // Get number of friends by using the user's id
{
    $string = "SELECT friend_id1, COUNT(friend_id2) as Total
    FROM myfriends
    WHERE friend_id1 = $id;";
    $result = mysqli_query($conn, $string);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $number = $row['Total'];
        }
        mysqli_free_result($result);
        return $number;
    } else {
        return null;
    }
}


function getMutualFriendCount($conn, $user1_id, $name) //Get mutual friend number with a person by using his name
{
    $user2_id = getFriendIdByName($conn, $name);
    $query = "SELECT COUNT(*) AS mutual_friend_count 
              FROM myfriends AS mf1
              INNER JOIN myfriends AS mf2 ON mf1.friend_id2 = mf2.friend_id2
              WHERE mf1.friend_id1 = $user1_id
              AND mf2.friend_id1 = $user2_id";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row['mutual_friend_count'];
    } else {
        return 0;
    }
}






?>