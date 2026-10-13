<?php
    $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    $listTitle = filter_input(INPUT_POST, 'list_title');
    $category = filter_input(INPUT_POST, 'category');

    if ($eventID === NULL || $eventID === FALSE || empty($listTitle) || empty($category)) {
        $error = "Invalid list details. Please fill out all fields.";
        include('error.php');
    } else {
        require_once('database.php');

        $query = 'INSERT INTO lists (eventID, listTitle, category)
                  VALUES (:eventID, :listTitle, :category)';

        $statement = $db->prepare($query);
        $statement->bindValue(':eventID', $eventID);
        $statement->bindValue(':listTitle', $listTitle);
        $statement->bindValue(':category', $category);

        $statement->execute();
        $statement->closeCursor();

        header('Location: checklists.php');
    }
?>