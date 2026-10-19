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

            <form action="register_user.php" method="post">
                
                <div id="data">
                        
                    <label>Username:</label>
                    <input type="text" name="username" required>
                    
                    <label>Password:</label>
                    <input type="password" name="password" required>
                    
                    <label>Email Address:</label>
                    <input type="text" name="emailAddress" required>
                
                </div>

                <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Register" />

                </div>

            </form>

            <p><a href="index.php" class="btn-add">Back To Login</a></p>

        </main>

        <?php include("footer.php"); ?>

    </body>
    
</html>