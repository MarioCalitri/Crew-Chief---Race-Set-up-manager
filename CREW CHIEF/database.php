<?php
    $dsn = 'mysql:host=localhost;dbname=crew_chief';
    $username = 'root';
    $password = ''; // Leave blank if using default XAMPP/MAMP settings without a password

    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*try {
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
        include('error.php');
        exit();
    }*/
?>