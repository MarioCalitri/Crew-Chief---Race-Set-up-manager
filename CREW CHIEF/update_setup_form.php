<?php
    require("database.php");

    // get setup ID
    $setupID = filter_input(INPUT_POST, 'setup_id', FILTER_VALIDATE_INT);

    if ($setupID === NULL || $setupID === FALSE) {
        $error = "Invalid setup ID";
        include('error.php');
        exit();
    }

// fetch setup record
$querySetup = 'SELECT * FROM setups WHERE setupID = :setupID';
$statement = $db->prepare($querySetup);
$statement->bindValue(':setupID', $setupID);
$statement->execute();
$statement->fetch();
$setup = $statement->fetchAll();
$statement->closeCursor();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Update Setup</title>
<link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Update Setup Sheet</h2>

        <form action="update_setup.php" method="post" id="update_setup_form">

            <input type="hidden" name="setup_id" value="<?php echo $setup['setupID']; ?>" />

            <label>Race Event:</label>
            <select name="event_id">
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['eventID']; ?>" 
                        <?php echo ($event['eventID'] == $setup['eventID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($event['eventName'] . ' - ' . $event['trackName'] . ' (' . $event['eventDate'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select><br />

            <h3>Tires & Stagger</h3>

            <label>LF Tire Size:</label>
            <input type="text" name="tire_size_lf" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeLF'] ?? ''); ?>" /><br />
            <label>LF Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_lf" value="<?php echo htmlspecialchars($setup['tirePressureLF'] ?? ''); ?>" /><br />

            <label>RF Tire Size:</label>
            <input type="text" name="tire_size_rf" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeRF'] ?? ''); ?>" /><br />
            <label>RF Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_rf" value="<?php echo htmlspecialchars($setup['tirePressureRF'] ?? ''); ?>" /><br />

            <label>LR Tire Size:</label>
            <input type="text" name="tire_size_lr" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeLR'] ?? ''); ?>" /><br />
            <label>LR Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_lr" value="<?php echo htmlspecialchars($setup['tirePressureLR'] ?? ''); ?>" /><br />

            <label>RR Tire Size:</label>
            <input type="text" name="tire_size_rr" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeRR'] ?? ''); ?>" /><br />
            <label>RR Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_rr" value="<?php echo htmlspecialchars($setup['tirePressureRR'] ?? ''); ?>" /><br />

            <label>Front Stagger (in):</label>
            <input type="number" step="0.25" name="front_stagger" value="<?php echo htmlspecialchars($setup['frontStagger'] ?? ''); ?>" /><br />

            <label>Rear Stagger (in):</label>
            <input type="number" step="0.25" name="rear_stagger" value="<?php echo htmlspecialchars($setup['rearStagger'] ?? ''); ?>" /><br />

            <h3>Wheel Weights & Percentages</h3>
            <label>LF Weight (lbs):</label>
            <input type="number" step="0.1" name="weight_lf" value="<?php echo htmlspecialchars($setup['weightLF'] ?? ''); ?>" /><br />

            <label>RF Weight (lbs):</label>
            <input type="number" step="0.1" name="weight_rf" value="<?php echo htmlspecialchars($setup['weightRF'] ?? ''); ?>" /><br />

            <label>LR Weight (lbs):</label>
            <input type="number" step="0.1" name="weight_lr" value="<?php echo htmlspecialchars($setup['weightLR'] ?? ''); ?>" /><br />

            <label>RR Weight (lbs):</label>
            <input type="number" step="0.1" name="weight_rr" value="<?php echo htmlspecialchars($setup['weightRR'] ?? ''); ?>" /><br />

            <label>Left Weight (%):</label>
            <input type="number" step="0.1" name="left_weight" value="<?php echo htmlspecialchars($setup['leftWeight'] ?? ''); ?>" /><br />

            <label>Cross Weight (%):</label>
            <input type="number" step="0.1" name="cross_weight" value="<?php echo htmlspecialchars($setup['crossWeight'] ?? ''); ?>" /><br />

            <label>Total Weight (lbs):</label>
            <input type="number" step="0.1" name="total_weight" value="<?php echo htmlspecialchars($setup['totalWeight'] ?? ''); ?>" /><br />

            <h3>Notes</h3>
            <textarea name="notes" rows="4" cols="50"><?php echo htmlspecialchars($setup['notes'] ?? ''); ?></textarea><br />

            <label>&nbsp;</label>
            <input type="submit" value="Update Setup Sheet" /><br />

        </form>

        <p><a href="index.php">View Setup List</a></p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>