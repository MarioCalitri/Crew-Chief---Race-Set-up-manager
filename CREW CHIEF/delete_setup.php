<?php
    require_once('database.php');

    // get ID from POST
    $setupID = filter_input(INPUT_POST, 'setup_id', FILTER_VALIDATE_INT);

    // validate and delete
    if ($setupID !== NULL && $setupID !== FALSE) {
        $query = 'DELETE FROM setups WHERE setupID = :setupID';
        $statement = $db->prepare($query);
        $statement->bindValue(':setupID', $setupID);
        $statement->execute();
        $statement->closeCursor();
    }
        
    // redirect to setup list
    header('Location: setups.php');
    
?>