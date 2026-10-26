<?php
    session_start();

?>

<!DOCTYPE html>

<html>

    <head>
        <title>Race Setup Manager - Register User</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
    </head>

    <body>

        <?php include("header.php"); ?>

        <main>
            <h2>Register User Confirmation</h2>

            <p>
                Thank you, <?php echo htmlspecialchars($_SESSION['username']); ?>
                for registering!
            </p>
 <form action="profiles.php" method="get" style="display: inline;">
            <input type="submit" value="View Profile" />
        </form>            
        <p><a href="index.php" class="btn-add">View Profile List</a></p>

        </main> 

        <?php include("footer.php"); ?>

    </body>

</html>