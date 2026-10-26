<!DOCTYPE html>

<html>

    <head>
        <title>Race Setup Manager - Register User</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
    </head>

    <body>

        <?php include("header.php"); ?>

        <main>
            <h2>Register User</h2>

            <form action="register_user.php" method="post" class="registration-form">
                <div class="formRow">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="formRow">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="emailAddress" required>
                </div>

                <div class="formRow">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="formActions">
                    <input type="submit" value="Register" class="btn-submit">
                    <a href="login.php" class="buttonLink">Back to Login</a>
                </div>
            </form>
            

        </main>

        <?php include("footer.php"); ?>

    </body>
    
</html>