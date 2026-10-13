<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once('database.php');

    // Fetch form data
    $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);

    // Fail if eventID is missing or invalid
    if ($eventID === NULL || $eventID === FALSE) {
        $error = "Invalid event ID. Please select a valid event from the form.";
        include('error.php');
        exit();
    }
    
    // Tire sizes & Pressures
    $sizeLF = filter_input(INPUT_POST, 'tire_size_lf');
    $sizeRF = filter_input(INPUT_POST, 'tire_size_rf');
    $sizeLR = filter_input(INPUT_POST, 'tire_size_lr');
    $sizeRR = filter_input(INPUT_POST, 'tire_size_rr');

    $lfPressure = filter_input(INPUT_POST, 'tire_pressure_lf', FILTER_VALIDATE_FLOAT);
    $rfPressure = filter_input(INPUT_POST, 'tire_pressure_rf', FILTER_VALIDATE_FLOAT);
    $lrPressure = filter_input(INPUT_POST, 'tire_pressure_lr', FILTER_VALIDATE_FLOAT);
    $rrPressure = filter_input(INPUT_POST, 'tire_pressure_rr', FILTER_VALIDATE_FLOAT);
    
    $frontStagger = filter_input(INPUT_POST, 'front_stagger', FILTER_VALIDATE_FLOAT);
    $rearStagger = filter_input(INPUT_POST, 'rear_stagger', FILTER_VALIDATE_FLOAT);
    
    $weightLF = filter_input(INPUT_POST, 'weight_lf', FILTER_VALIDATE_FLOAT);
    $weightRF = filter_input(INPUT_POST, 'weight_rf', FILTER_VALIDATE_FLOAT);
    $weightLR = filter_input(INPUT_POST, 'weight_lr', FILTER_VALIDATE_FLOAT);
    $weightRR = filter_input(INPUT_POST, 'weight_rr', FILTER_VALIDATE_FLOAT);
    
    $weightLeft = filter_input(INPUT_POST, 'weight_left', FILTER_VALIDATE_FLOAT);
    $crossWeight = filter_input(INPUT_POST, 'cross_weight', FILTER_VALIDATE_FLOAT);
    $totalWeight = filter_input(INPUT_POST, 'total_weight', FILTER_VALIDATE_FLOAT);
    $notes = filter_input(INPUT_POST, 'notes');

    // INSERT into setups table
    $query = 'INSERT INTO setups 
                (eventID, tireSizeLF, tireSizeRF, tireSizeLR, tireSizeRR, 
                 tirePressureLF, tirePressureRF, tirePressureLR, tirePressureRR,
                 frontStagger, rearStagger, weightLF, weightRF, weightLR, weightRR, 
                 leftWeight, crossWeight, totalWeight, notes)
            VALUES
                (:eventID, :sizeLF, :sizeRF, :sizeLR, :sizeRR, 
                 :lfPressure, :rfPressure, :lrPressure, :rrPressure,
                 :frontStagger, :rearStagger, :weightLF, :weightRF, :weightLR, :weightRR, 
                 :weightLeft, :crossWeight, :totalWeight, :notes)';

    $statement = $db->prepare($query);
    $statement->bindValue(':eventID', $eventID);
    $statement->bindValue(':sizeLF', $sizeLF);
    $statement->bindValue(':sizeRF', $sizeRF);
    $statement->bindValue(':sizeLR', $sizeLR);
    $statement->bindValue(':sizeRR', $sizeRR);
    $statement->bindValue(':lfPressure', $lfPressure);
    $statement->bindValue(':rfPressure', $rfPressure);
    $statement->bindValue(':lrPressure', $lrPressure);
    $statement->bindValue(':rrPressure', $rrPressure);
    $statement->bindValue(':frontStagger', $frontStagger);
    $statement->bindValue(':rearStagger', $rearStagger);
    $statement->bindValue(':weightLF', $weightLF);
    $statement->bindValue(':weightRF', $weightRF);
    $statement->bindValue(':weightLR', $weightLR);
    $statement->bindValue(':weightRR', $weightRR);
    $statement->bindValue(':weightLeft', $weightLeft);
    $statement->bindValue(':crossWeight', $crossWeight);
    $statement->bindValue(':totalWeight', $totalWeight);
    $statement->bindValue(':notes', $notes);

    $statement->execute();  
    $statement->closeCursor();

    // Redirect back to setup list
    header('Location: index.php');
    exit();
?>