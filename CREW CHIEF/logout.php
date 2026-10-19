<?php
    session_start();

    $_SESSION = []; // clears all session data
    session_destroy(); // clean up the session id

    header("Location: login_form.php");
    die();
?>