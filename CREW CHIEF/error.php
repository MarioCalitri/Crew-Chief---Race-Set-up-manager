<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Error</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <h2>Database / Input Error</h2>
        <p><?php echo htmlspecialchars($error); ?></p>
        <p><a href="index.php">Back to Setup List</a></p>
    </main>

    <?php include('footer.php'); ?>

    </body>
</html>
