<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once('database.php');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Capture and validate required fields
    $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    
    if (!$eventID) {
        $error = "Invalid or missing race event. Please go back and select an event.";
        include('error.php');
        exit();
    }

    // Capture optional tire sizes (strings)
    $tireSizeLF = filter_input(INPUT_POST, 'tire_size_lf') ?: null;
    $tireSizeRF = filter_input(INPUT_POST, 'tire_size_rf') ?: null;
    $tireSizeLR = filter_input(INPUT_POST, 'tire_size_lr') ?: null;
    $tireSizeRR = filter_input(INPUT_POST, 'tire_size_rr') ?: null;

    // Capture optional numeric pressures and weights
    $tirePressureLF = filter_input(INPUT_POST, 'tire_pressure_lf', FILTER_VALIDATE_FLOAT) ?: null;
    $tirePressureRF = filter_input(INPUT_POST, 'tire_pressure_rf', FILTER_VALIDATE_FLOAT) ?: null;
    $tirePressureLR = filter_input(INPUT_POST, 'tire_pressure_lr', FILTER_VALIDATE_FLOAT) ?: null;
    $tirePressureRR = filter_input(INPUT_POST, 'tire_pressure_rr', FILTER_VALIDATE_FLOAT) ?: null;

    $frontStagger = filter_input(INPUT_POST, 'front_stagger', FILTER_VALIDATE_FLOAT) ?: null;
    $rearStagger = filter_input(INPUT_POST, 'rear_stagger', FILTER_VALIDATE_FLOAT) ?: null;

    $weightLF = filter_input(INPUT_POST, 'weight_lf', FILTER_VALIDATE_FLOAT) ?: null;
    $weightRF = filter_input(INPUT_POST, 'weight_rf', FILTER_VALIDATE_FLOAT) ?: null;
    $weightLR = filter_input(INPUT_POST, 'weight_lr', FILTER_VALIDATE_FLOAT) ?: null;
    $weightRR = filter_input(INPUT_POST, 'weight_rr', FILTER_VALIDATE_FLOAT) ?: null;

    $leftWeight = filter_input(INPUT_POST, 'weight_left', FILTER_VALIDATE_FLOAT) ?: null;
    $crossWeight = filter_input(INPUT_POST, 'cross_weight', FILTER_VALIDATE_FLOAT) ?: null;
    $totalWeight = filter_input(INPUT_POST, 'total_weight', FILTER_VALIDATE_FLOAT) ?: null;

    $notes = filter_input(INPUT_POST, 'notes');

    // Insert query matching exact database column names from phpMyAdmin
    $query = 'INSERT INTO setups (
                eventID, tireSizeLF, tirePressureLF, tireSizeRF, tirePressureRF, 
                tireSizeLR, tirePressureLR, tireSizeRR, tirePressureRR, 
                frontStagger, rearStagger, weightLF, weightRF, weightLR, weightRR, 
                leftWeight, crossWeight, totalWeight, notes
              ) VALUES (
                :eventID, :tireSizeLF, :tirePressureLF, :tireSizeRF, :tirePressureRF, 
                :tireSizeLR, :tirePressureLR, :tireSizeRR, :tirePressureRR, 
                :frontStagger, :rearStagger, :weightLF, :weightRF, :weightLR, :weightRR, 
                :leftWeight, :crossWeight, :totalWeight, :notes
              )';

    $statement = $db->prepare($query);

    // Bind parameters with proper NULL handling
    $statement->bindValue(':eventID', $eventID, PDO::PARAM_INT);
    
    $statement->bindValue(':tireSizeLF', $tireSizeLF);
    $statement->bindValue(':tirePressureLF', $tirePressureLF, $tirePressureLF === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':tireSizeRF', $tireSizeRF);
    $statement->bindValue(':tirePressureRF', $tirePressureRF, $tirePressureRF === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':tireSizeLR', $tireSizeLR);
    $statement->bindValue(':tirePressureLR', $tirePressureLR, $tirePressureLR === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':tireSizeRR', $tireSizeRR);
    $statement->bindValue(':tirePressureRR', $tirePressureRR, $tirePressureRR === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':frontStagger', $frontStagger, $frontStagger === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':rearStagger', $rearStagger, $rearStagger === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':weightLF', $weightLF, $weightLF === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':weightRF', $weightRF, $weightRF === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':weightLR', $weightLR, $weightLR === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':weightRR', $weightRR, $weightRR === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':leftWeight', $leftWeight, $leftWeight === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':crossWeight', $crossWeight, $crossWeight === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $statement->bindValue(':totalWeight', $totalWeight, $totalWeight === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $statement->bindValue(':notes', $notes);

    $statement->execute();
    $statement->closeCursor();

    header('Location: setups.php');
    exit();
?>