<?php
function isValidEmail($email) //Email validation to check whether its in correct format
{
    return preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email);
}

function isValidProfileName($profileName) // Profile name validation to check if it contains only letters
{
    return preg_match("/^[a-zA-Z]+$/", $profileName);
}

function isValidPassword($password) //Password validation to check if it contains only letter and number
{
    return preg_match("/^[a-zA-Z0-9]+$/", $password);
}


?>