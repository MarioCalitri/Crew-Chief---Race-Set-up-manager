<?php
    require_once ("database.php");

    // fetch all events for drop down
    $queryEvents = 'SELECT eventID, eventName, trackName, eventDate FROM race_events ORDER BY eventDate DESC';
    $statement = $db->prepare($queryEvents);
    $statement->execute();
    $events = $statement->fetchAll();
    $statement->closeCursor();
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Crew Chief - Add Setup</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head> 
<body>

    <?php include('header.php'); ?>

    <main>
        <h2>Add New Setup</h2>
        
        <form action="add_setup.php" method="post" id="add_setup_form">

            <label>Race Event:</label>
            <select name="event_id">
                <?php foreach ($events as $event) : ?>
                    <option value="<?php echo $event['eventID']; ?>">
                        <?php echo htmlspecialchars($event['eventName']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br />


            <h3>Tires & Stagger</h3>

            <label>LF Tire Size:</label>

            <input type="text" id="tire_size_lf" name="tire_size_lf" /><br />
            <label>LF Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_lf" /><br />

            <label>RF Tire Size:</label>
            <input type="text" id="tire_size_rf" name="tire_size_rf" /><br />
            <label>RF Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_rf" /><br />

            <label>LR Tire Size:</label>
            <input type="text" id="tire_size_lr" name="tire_size_lr" /><br />
            <label>LR Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_lr" /><br />

            <label>RR Tire Size:</label>
            <input type="text" id="tire_size_rr" name="tire_size_rr" /><br />
            <label>RR Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_rr" /><br />

            <label>Front Stagger:</label>
            <input type="number" step="0.25" id="front_stagger" name="front_stagger" readonly /><br />

            <label>Rear Stagger:</label>
            <input type="number" step="0.25" id="rear_stagger" name="rear_stagger" readonly /><br />
            
            <h3>Wheel Weights & Percentages</h3>

            <label>LF Weight (lbs):</label>
            <input type="number" step="1.0" id="weight_lf" name="weight_lf" required><br />

            <label>RF Weight (lbs):</label>
            <input type="number" step="1.0" id="weight_rf" name="weight_rf" required><br />

            <label>LR Weight (lbs):</label>
            <input type="number" step="1.0" id="weight_lr" name="weight_lr" required><br />

            <label>RR Weight (lbs):</label>
            <input type="number" step="1.0" id="weight_rr" name="weight_rr" required><br />    

            <label>Left Side Weight (lbs):</label>
            <input type="number" step="0.1" id="left_weight" name="weight_left" readonly><br />

            <label>Cross Weight (%) = :</label>
            <input type="number" step="0.1" id="cross_weight" name="cross_weight" readonly><br />

            <label>Total Weight (lbs):</label>
            <input type="number" step="0.1" id="total_weight" name="total_weight" readonlt><br /> 

            <h3>Notes</h3>
            <textarea name="notes" rows="4" cols="50"></textarea><br />

            <label>&nbsp;</label>
            <input type="submit" value="Save Setup"><br />

        </form>

        <div id="buttons">
                    <label>&nbsp;</label>
                    <form action="setups.php" method="get" style="display: inline;">
                        <input type="submit" value="VIEW SETUP LIST" />
                </form>
            </div>
    </main>

    <?php include('footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- TIRE STAGGERS ---
            const lfInput = document.getElementById('tire_size_lf');
            const rfInput = document.getElementById('tire_size_rf');
            const frontStaggerInput = document.getElementById('front_stagger');

            const lrInput = document.getElementById('tire_size_lr');
            const rrInput = document.getElementById('tire_size_rr');
            const rearStaggerInput = document.getElementById('rear_stagger');

            function calculateFrontStagger() {
                const lf = parseFloat(lfInput.value) || 0;
                const rf = parseFloat(rfInput.value) || 0;
                const stagger = Math.abs(lf - rf);
                frontStaggerInput.value = stagger ? stagger.toFixed(2) : '';
            }

            function calculateRearStagger() {
                const lr = parseFloat(lrInput.value) || 0;
                const rr = parseFloat(rrInput.value) || 0;
                const stagger = Math.abs(lr - rr);
                rearStaggerInput.value = stagger ? stagger.toFixed(2) : '';
            }

            // --- WEIGHTS ---
            const wLF = document.getElementById('weight_lf');
            const wRF = document.getElementById('weight_rf');
            const wLR = document.getElementById('weight_lr');
            const wRR = document.getElementById('weight_rr');
            const leftWeightInput = document.getElementById('left_weight');
            const crossWeightInput = document.getElementById('cross_weight');
            const totalWeightInput = document.getElementById('total_weight');

            function calculateWeights() {
                const lf = parseFloat(wLF.value) || 0;
                const rf = parseFloat(wRF.value) || 0;
                const lr = parseFloat(wLR.value) || 0;
                const rr = parseFloat(wRR.value) || 0;

                const total = lf + rf + lr + rr;
                const leftPounds = lf + lr;
                
                // calculate percentages 
                const leftPercent = total > 0 ? (leftPounds / total) * 100 : 0;
                const crossPercent = total > 0 ? ((rf + lr) / total) * 100 : 0;

                totalWeightInput.value = total > 0 ? total.toFixed(1) : '';
                leftWeightInput.value = leftPercent > 0 ? leftPercent.toFixed(1) : '';
                crossWeightInput.value = crossPercent > 0 ? crossPercent.toFixed(1) : '';
            }

            // runs calculations on input
            calculateFrontStagger();
            calculateRearStagger();
            calculateWeights();

            // event listeners for tires
            if (lfInput && rfInput && frontStaggerInput) {
                lfInput.addEventListener('input', calculateFrontStagger);
                rfInput.addEventListener('input', calculateFrontStagger);
            }
            if (lrInput && rrInput && rearStaggerInput) {
                lrInput.addEventListener('input', calculateRearStagger);
                rrInput.addEventListener('input', calculateRearStagger);
            }

            // event listeners for weights
            [wLF, wRF, wLR, wRR].forEach(input => {
                if (input) {
                    input.addEventListener('input', calculateWeights);
                }
            });
        });
    </script>
</body>
</html>

    
