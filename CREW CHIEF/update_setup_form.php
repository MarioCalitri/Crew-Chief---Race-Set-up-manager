<?php
    require("database.php");

    // get setup ID
    $setupID = filter_input(INPUT_POST, 'setup_id', FILTER_VALIDATE_INT);
    if (!$setupID) {
        $setupID = filter_input(INPUT_GET, 'setup_id', FILTER_VALIDATE_INT);
    }

    if ($setupID === NULL || $setupID === FALSE) {
        $error = "Invalid setup ID";
        include('error.php');
        exit();
    }
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
$setup = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

$queryEvents = 'SELECT * FROM race_events';
$statementEvents = $db->prepare($queryEvents);
$statementEvents->execute();
$events = $statementEvents->fetchAll(PDO::FETCH_ASSOC);
$statementEvents->closeCursor();

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

            <form action="update_setup.php" method="post" id="update_setup_form" onsubmit="calculateFrontStagger(); calculateRearStagger(); calculateWeights();">
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
            <input type="text" id="tire_size_lf" name="tire_size_lf" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeLF'] ?? ''); ?>" /><br />
            <label>LF Pressure (PSI):</label>
            <input type="number" step="1.0" name="tire_pressure_lf" value="<?php echo htmlspecialchars($setup['tirePressureLF'] ?? ''); ?>" /><br />

            <label>RF Tire Size:</label>
            <input type="text" id="tire_size_rf" name="tire_size_rf" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeRF'] ?? ''); ?>" /><br />
            <label>RF Pressure (PSI):</label>
            <input type="number" step="1.0" name="tire_pressure_rf" value="<?php echo htmlspecialchars($setup['tirePressureRF'] ?? ''); ?>" /><br />

            <label>LR Tire Size:</label>
            <input type="text" id="tire_size_lr" name="tire_size_lr" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeLR'] ?? ''); ?>" /><br />
            <label>LR Pressure (PSI):</label>
            <input type="number" step="1.0" name="tire_pressure_lr" value="<?php echo htmlspecialchars($setup['tirePressureLR'] ?? ''); ?>" /><br />

            <label>RR Tire Size:</label>
            <input type="text" id="tire_size_rr" name="tire_size_rr" maxlength="20" value="<?php echo htmlspecialchars($setup['tireSizeRR'] ?? ''); ?>" /><br />
            <label>RR Pressure (PSI):</label>
            <input type="number" step="1,0" name="tire_pressure_rr" value="<?php echo htmlspecialchars($setup['tirePressureRR'] ?? ''); ?>" /><br />

            <label>Front Stagger (in):</label>
            <input type="number" step="0.25" id="front_stagger" name="front_stagger" value="<?php echo htmlspecialchars($setup['frontStagger'] ?? ''); ?>" readonly /><br />

            <label>Rear Stagger (in):</label>
            <input type="number" step="0.25" id="rear_stagger" name="rear_stagger" value="<?php echo htmlspecialchars($setup['rearStagger'] ?? ''); ?>" readonly /><br />

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

        <form action="setups.php" method="get" style="display: inline;">
                        <input type="submit" value="View Setup List" />
                    </form>

    </main>

    <?php include("footer.php"); ?>

<script>
        // --- GLOBAL CALCULATION FUNCTIONS ---
        function calculateFrontStagger() {
            const lfInput = document.getElementById('tire_size_lf');
            const rfInput = document.getElementById('tire_size_rf');
            const frontStaggerInput = document.getElementById('front_stagger');
            
            if (!lfInput || !rfInput || !frontStaggerInput) return;

            const lf = parseFloat(lfInput.value) || 0;
            const rf = parseFloat(rfInput.value) || 0;
            const stagger = Math.abs(lf - rf);
            frontStaggerInput.value = stagger ? stagger.toFixed(2) : '';
        }

        function calculateRearStagger() {
            const lrInput = document.getElementById('tire_size_lr');
            const rrInput = document.getElementById('tire_size_rr');
            const rearStaggerInput = document.getElementById('rear_stagger');
            
            if (!lrInput || !rrInput || !rearStaggerInput) return;

            const lr = parseFloat(lrInput.value) || 0;
            const rr = parseFloat(rrInput.value) || 0;
            const stagger = Math.abs(lr - rr);
            rearStaggerInput.value = stagger ? stagger.toFixed(2) : '';
        }

        function calculateWeights() {
            const wLF = document.getElementsByName('weight_lf')[0];
            const wRF = document.getElementsByName('weight_rf')[0];
            const wLR = document.getElementsByName('weight_lr')[0];
            const wRR = document.getElementsByName('weight_rr')[0];
            const leftWeightInput = document.getElementsByName('left_weight')[0];
            const crossWeightInput = document.getElementsByName('cross_weight')[0];
            const totalWeightInput = document.getElementsByName('total_weight')[0];

            if (!wLF || !wRF || !wLR || !wRR) return;

            const lf = parseFloat(wLF.value) || 0;
            const rf = parseFloat(wRF.value) || 0;
            const lr = parseFloat(wLR.value) || 0;
            const rr = parseFloat(wRR.value) || 0;

            const total = lf + rf + lr + rr;
            const leftPounds = lf + lr;
            
            const leftPercent = total > 0 ? (leftPounds / total) * 100 : 0;
            const crossPercent = total > 0 ? ((rf + lr) / total) * 100 : 0;

            if (totalWeightInput) totalWeightInput.value = total > 0 ? total.toFixed(1) : '';
            if (leftWeightInput) leftWeightInput.value = leftPercent > 0 ? leftPercent.toFixed(1) : '';
            if (crossWeightInput) crossWeightInput.value = crossPercent > 0 ? crossPercent.toFixed(1) : '';
        }

        // --- EVENT LISTENERS ON LOAD ---
        document.addEventListener('DOMContentLoaded', function() {
            const lfInput = document.getElementById('tire_size_lf');
            const rfInput = document.getElementById('tire_size_rf');
            const lrInput = document.getElementById('tire_size_lr');
            const rrInput = document.getElementById('tire_size_rr');

            const wLF = document.getElementsByName('weight_lf')[0];
            const wRF = document.getElementsByName('weight_rf')[0];
            const wLR = document.getElementsByName('weight_lr')[0];
            const wRR = document.getElementsByName('weight_rr')[0];

            // Initial calculations on page load
            calculateFrontStagger();
            calculateRearStagger();
            calculateWeights();

            // Bind dynamic listeners for tire sizes
            [lfInput, rfInput].forEach(input => {
                if (input) input.addEventListener('input', calculateFrontStagger);
            });
            [lrInput, rrInput].forEach(input => {
                if (input) input.addEventListener('input', calculateRearStagger);
            });

            // Bind dynamic listeners for weights
            [wLF, wRF, wLR, wRR].forEach(input => {
                if (input) input.addEventListener('input', calculateWeights);
            });
        });
    </script>