<?php


function CheckFriendsTable($conn) //Check if friends table exists or not
{
    $str = "SHOW TABLES LIKE 'friends';";
    $result = @mysqli_query($conn, $str);
    if (mysqli_num_rows($result) > 0) {
        mysqli_free_result($result);
        return true;
    } else {
        return false;
    }

}
function CheckMyFriendsTable($conn) // Check if myfriends table exists or not
{
    $str = "SHOW TABLES LIKE 'myfriends';";
    $result = @mysqli_query($conn, $str);
    if (mysqli_num_rows($result) > 0) {
        mysqli_free_result($result);
        return true;
    } else {
        return false;
    }
}

function CreateTableFriends($conn) //Create friends table if doesn't exist
{
    $sql = "CREATE TABLE IF NOT EXISTS `friends` (
    `friend_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `friend_email` varchar(50) UNIQUE NOT NULL,
    `password` varchar(20) NOT NULL,
    `profile_name` varchar(30) NOT NULL,
    `date_started` date NOT NULL,
    `num_of_friends` int UNSIGNED 
  ); ";
    $result = @mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function InsertDataFriends($conn) //Insert data into friends table
{
    $sql = "INSERT INTO `friends` (`friend_email`, `password`, `profile_name`, `date_started`, `num_of_friends`) VALUES
    ('eutting0@washington.edu', 'Password1', 'Ethelred Utting', '2023-09-29', 4),
    ('scooksley1@deviantart.com', 'Secure123', 'Samaria Cooksley', '2022-02-17', 4),
    ('tgieves2@tripadvisor.com', 'Pssw0rd12', 'Tasia Gieves', '2020-08-17', 2),
    ('dsanchez3@engadget.com', 'RandomPwd7', 'Damara Sanchez', '2022-06-01', 1),
    ('hlawdham4@multiply.com', 'SecretPwd8', 'Hamish Lawdham', '2020-05-24', 4),
    ('tajam5@fc2.com', 'Passw0rd12', 'Terrel Ajam', '2021-09-01', 2),
    ('sasp6@acquirethisname.com', 'Pw5aFg9', 'Stevie Asp', '2020-08-09', 1),
    ('emordanti7@ft.com', 'Emlyn123', 'Emlyn Mordanti', '2022-02-17', 1),
    ('kstrivens8@de.vu', 'Kath123', 'Katherine Strivens', '2020-12-15', 1),
    ('jkirimaa9@xrea.com', 'Juliett9', 'Juliette Kirimaa', '2021-09-07', 0);";

    $result = @mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function CreateTableMyFriends($conn) //Create myfriends table if doesn't exist
{
    $sql = "CREATE TABLE IF NOT EXISTS myfriends(
        friend_id1 int NOT NULL,
        friend_id2 int NOT NUll,
        PRIMARY KEY(friend_id1, friend_id2),
        FOREIGN Key (friend_id1) REFERENCES friends (friend_id),
        FOREIGN Key (friend_id2) REFERENCES friends (friend_id)
        );";
    $result = @mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function InsertDataMyFriends($conn) // Insert data into myfriends table
{
    $sql = "INSERT INTO myfriends (friend_id1, friend_id2)
    VALUES (1,2),
    (1,5),
    (1,7),
    (1,10),
    (2,1),
    (2,5),
    (2,4),
    (2,6),
    (3,1),
    (3,2),
    (4,2),
    (5,1),
    (5,2),
    (5,10),
    (5,6),
    (6,5),
    (6,3),
    (7,1),
    (8,10),
    (9,4);";
    $result = @mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}


?>