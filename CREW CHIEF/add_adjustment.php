<?php
    $sessionID = filter_input(INPUT_POST, 'session_id', FILTER_VALIDATE_INT);
    $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    $changeMade = filter_input(INPUT_POST, 'change_made');
    $handlingNotes = filter_input(INPUT_POST, 'handling_notes');

    if ($sessionID === NULL || $sessionID === FALSE || empty($changeMade)) {
        $error = "Invalid adjustment data. Please specify the session and changes made.";
        include('error.php');
    } else {
        require_once('database.php');

        $query = 'INSERT INTO pit_adjustments (eventID, sessionID, changeMade, carHandlingNotes)
                  VALUES (:eventID, :sessionID, :changeMade, :handlingNotes)';

        $statement = $db->prepare($query);
        $statement->bindValue(':eventID', $eventID);
        $statement->bindValue(':sessionID', $sessionID);
        $statement->bindValue(':changeMade', $changeMade);
        $statement->bindValue(':handlingNotes', $handlingNotes);

        $statement->execute();
        $statement->closeCursor();

        header('Location: pit_adjustments.php');
    }
?>