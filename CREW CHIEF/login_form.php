<?php
    session_start();

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Race Setup Manager - Login</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
    </head>

    <body>

        <?php include("header.php"); ?>

        <main>
            <h2>Login</h2>

                <?php
                if (isset($_SESSION['user_error'])) {
                    $error_msg = $_SESSION['user_error'];
                    echo '<p id="lockout-error" style="color: red; font-weight: bold;">' . htmlspecialchars($error_msg) . '</p>';
                    
                    // extract numbers if a lockout timer string is passed
                    preg_match('/\d+/', $error_msg, $matches);
                    $lockout_seconds = isset($matches[0]) ? intval($matches[0]) : 0;
                    
                    if ($lockout_seconds > 0) {
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                let seconds = ' . $lockout_seconds . ';
                                const errorP = document.getElementById("lockout-error");
                                
                                const countdownTimer = setInterval(() => {
                                    seconds--;
                                    if (seconds > 0) {
                                        errorP.textContent = "Account locked. Try again in " + seconds + " second(s).";
                                    } else {
                                        errorP.textContent = "Lockout period has expired. Please try logging in again.";
                                        clearInterval(countdownTimer);
                                    }
                                }, 1000);
                            });
                        </script>';
                    }
                    
                    unset($_SESSION['user_error']);
                }
            ?>

            <form action="login.php" method="post" id="login_form">
                
            <div id="data">
                <label>Username:</label>
                <input type="text" name="username" required>

                <label>Password:</label>
                <input type="password" name="password" required>

            </div>

            <div id="buttons">

                <label>&nbsp;</label>
                <input type="submit" value="Login" /><br />

            </div>

            </form>

            <div id="buttons">
                <label>&nbsp;</label>
                <form action="register_user_form.php" method="get" style="display: inline;">
                    <input type="submit" value="Register New User" />
                </form>
            </div>
        </main>

        <?php include("footer.php"); ?>

    </body>

</html>
