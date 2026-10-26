<?php
    session_start();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Race Setup Manager - Error</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
    </head>

    <body>

        <?php include("header.php"); ?>

        <main>
            <h2>Registration Error</h2>

            <?php
                if (isset($_SESSION['user_error'])) {
                    echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['user_error']) . '</p>';
                    unset($_SESSION['user_error']);
                }
            ?>
            <div id="buttons">
                <label>&nbsp;</label>
                <form action="register_user_form.php" method="get" style="display: inline;">
                    <input type="submit" value="Back to Registration" />
                </form>
            </div>
        </main>

        <?php include("footer.php"); ?>

    </body>

</html>