<?php
    session_start();
    
    // Ensure the user is actually logged in
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
        header("Location: login_form.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Race Setup Manager - Login Confirmation</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
    </head>
    <body>

        <?php include("header.php"); ?>

        <main>
            <h2>Login Successful!</h2>

            <p>
                Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are now logged in.
            </p>

            <p><a href="index.php" class="button">Go to Dashboard</a></p>
        </main> 

        <?php include("footer.php"); ?>

    </body>
</html>