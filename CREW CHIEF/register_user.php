<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();
    
    // get date from form and assign to variables
    $user_name = filter_input(INPUT_POST, 'username');
    $user_password = filter_input(INPUT_POST, 'password');
    $email_address = filter_input(INPUT_POST, 'emailAddress');

    $hash = password_hash($user_password, PASSWORD_DEFAULT);

    require_once("database.php"); 

    $queryUsers = 'SELECT * FROM registrations';

    $statement = $db->prepare($queryUsers);
    $statement->execute();
    $users = $statement->fetchAll();
    $statement->closeCursor();

    foreach ($users as $user) {
        if ($user_name == $user["userName"]) {
            $_SESSION["user_error"] = "Inavalid data, duplicate Username. Try again.";
            $url = "user_error.php";
            header("Location: " . $url);
            die();
        }
    }

    if ($user_name == null || $user_password == null || $email_address == null) {
        $_SESSION["user_error"] = "Invalid user data, check all fields and try again.";
        $url = "user_error.php";
            header("Location: " . $url);
            die();
        }
    

    // add Registration

    $query = 'INSERT INTO registrations (userName, password, emailAddress)
        VALUES (:userName, :password, :emailAddress)';

    $statement = $db->prepare($query);

    $statement->bindValue(':userName', $user_name);
    $statement->bindValue(':password', $hash);
    $statement->bindValue(':emailAddress', $email_address);    

    $statement->execute();    
    $statement->closeCursor();

    $_SESSION["username"] = $user_name;
    $_SESSION["isLoggedIn"] = 1;

    $url = "register_user_confirmation.php";
    header("Location: " . $url);
    die();

?>

    
