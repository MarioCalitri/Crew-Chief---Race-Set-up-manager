<?php

    // get form data
    $setupID = filter_input(INPUT_POST, 'setup_id', FILTER_VALIDATE_INT);
    $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    
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
    $leftWeight = filter_input(INPUT_POST, 'left_weight', FILTER_VALIDATE_FLOAT);
    $crossWeight = filter_input(INPUT_POST, 'cross_weight', FILTER_VALIDATE_FLOAT);
    $total_weight = filter_input(INPUT_POST, 'total_weight', FILTER_VALIDATE_FLOAT);
    $notes = filter_input(INPUT_POST, 'notes');

    // Validate inputs
    if ($setupID === NULL || $setupID === FALSE || $eventID === NULL || $eventID === FALSE) {
        $error = "Invalid setup or event data. Check all fields and try again.";
        include('error.php');
    } else {
        require_once('database.php');
        
    $query = 'UPDATE setups
            SET eventID = :eventID,
                tireSizeLF = :sizeLF, tireSizeRF = :sizeRF, tireSizeLR = :sizeLR, tireSizeRR = :sizeRR, 
                tirePressureLF = :lfPressure, tirePressureRF = :rfPressure, tirePressureLR = :lrPressure, tirePressureRR = rrPressure,
                frontStagger = :frontStagger, rearStagger = :rearStagger, 
                weightLF = :weightLF, weightRF = :weightRF, weightLR = :weightLR, weightRR = :weightRR, 
                leftWeight = :leftWeight, crossWeight = :crossWeight, totalWeight = :totalWeight, notes = :notes
                WHERE setupID = :setupID';

                $statement = $db->prepare($query);
        $statement->bindValue(':setupID', $setupID);
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
        $statement->bindValue(':leftWeight', $leftWeight);
        $statement->bindValue(':crossWeight', $crossWeight);
        $statement->bindValue(':totalWeight', $totalWeight);
        $statement->bindValue(':notes', $notes);

        $statement->execute();
        $statement->closeCursor();

        header('Location: index.php');
    }

?>