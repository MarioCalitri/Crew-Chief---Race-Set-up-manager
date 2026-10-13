<?php
    $listID = filter_input(INPUT_POST, 'list_id', FILTER_VALIDATE_INT);
    $itemName = filter_input(INPUT_POST, 'item_name');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if ($listID === NULL || $listID === FALSE || empty($itemName) || $quantity === NULL || $quantity === FALSE) {
        $error = "Invalid item details.";
        include('error.php');
    } else {
        require_once('database.php');

        $query = 'INSERT INTO list_items (listID, itemName, quantity, isCompleted)
                  VALUES (:listID, :itemName, :quantity, 0)';

        $statement = $db->prepare($query);
        $statement->bindValue(':listID', $listID);
        $statement->bindValue(':itemName', $itemName);
        $statement->bindValue(':quantity', $quantity);

        $statement->execute();
        $statement->closeCursor();

        header('Location: checklists.php');
    }
?>