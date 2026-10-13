<?php
    $itemID = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $currentStatus = filter_input(INPUT_POST, 'current_status', FILTER_VALIDATE_INT);

    if ($itemID !== NULL && $itemID !== FALSE) {
        require_once('database.php');

        $newStatus = ($currentStatus == 1) ? 0 : 1;

        $query = 'UPDATE list_items SET isCompleted = :newStatus WHERE itemID = :itemID';
        $statement = $db->prepare($query);
        $statement->bindValue(':newStatus', $newStatus);
        $statement->bindValue(':itemID', $itemID);

        $statement->execute();
        $statement->closeCursor();
    }

    header('Location: checklists.php');
?>