<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    require_once("database.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        if ($username && $password) {
            // Fetch user from registrations table
            $query = 'SELECT * FROM registrations WHERE userName = :username';
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            // Verify password and user existence
            if ($user && password_verify($password, $user['password'])) {
                // SUCCESS: Set session variables
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['username'] = $user['userName'];
                
                // CRITICAL: Save the user's ID so dashboard queries work!
                // (Make sure 'registrationID' matches your primary key column name in MySQL)
                $_SESSION['user_id'] = $user['registrationID'] ?? $user['userID'] ?? 1; 

                header('Location: index.php');
                exit();
            } else {
                $_SESSION['user_error'] = "Invalid username or password.";
                header('Location: login_form.php');
                exit();
            }
        } else {
            // Empty fields submitted
            $_SESSION['user_error'] = "Please fill in all fields.";
            header('Location: login_form.php');
            exit();
        }
    } else {
        // Fallback if someone accesses login.php directly via GET
        header('Location: login_form.php');
        exit();
    }
?>