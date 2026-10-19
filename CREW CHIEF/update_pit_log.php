<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $logID = filter_input(INPUT_POST, 'log_id', FILTER_VALIDATE_INT);
    $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    $sessionName = filter_input(INPUT_POST, 'session_name');

    $hotLF = filter_input(INPUT_POST, 'hot_pressure_lf', FILTER_VALIDATE_FLOAT) ?: null;
    $hotRF = filter_input(INPUT_POST, 'hot_pressure_rf', FILTER_VALIDATE_FLOAT) ?: null;
    $hotLR = filter_input(INPUT_POST, 'hot_pressure_lr', FILTER_VALIDATE_FLOAT) ?: null;
    $hotRR = filter_input(INPUT_POST, 'hot_pressure_rr', FILTER_VALIDATE_FLOAT) ?: null;

    $adjustments = filter_input(INPUT_POST, 'adjustments');
    $notes = filter_input(INPUT_POST, 'notes');

    if (!$logID || !$eventID || empty($sessionName)) {
        $error = "Invalid form submission or missing required fields.";
        include('error.php');
        exit();
    }

    require_once('database.php');

    $query = 'UPDATE pit_logs 
              SET eventID = :eventID, 
                  sessionName = :sessionName, 
                  hotPressureLF = :hotLF, 
                  hotPressureRF = :hotRF, 
                  hotPressureLR = :hotLR, 
                  hotPressureRR = :hotRR, 
                  adjustments = :adjustments, 
                  notes = :notes 
              WHERE logID = :logID';

    $statement = $db->prepare($query);
    $statement->bindValue(':eventID', $eventID, PDO::PARAM_INT);
    $statement->bindValue(':sessionName', $sessionName);
    $statement->bindValue(':hotLF', $hotLF, $hotLF === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':hotRF', $hotRF, $hotRF === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':hotLR', $hotLR, $hotLR === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':hotRR', $hotRR, $hotRR === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':adjustments', $adjustments);
    $statement->bindValue(':notes', $notes);
    $statement->bindValue(':logID', $logID, PDO::PARAM_INT);

    $statement->execute();
    $statement->closeCursor();

    header('Location: pit_logs.php');
    exit();
?>