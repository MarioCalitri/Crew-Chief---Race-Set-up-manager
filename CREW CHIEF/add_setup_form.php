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
            <input type="text" name="tire_size_lf" /><br />
            <label>LF Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_lf" /><br />

            <label>RF Tire Size:</label>
            <input type="text" name="tire_size_rf" /><br />
            <label>RF Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_rf" /><br />

            <label>LR Tire Size:</label>
            <input type="text" name="tire_size_lr" /><br />
            <label>LR Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_lr" /><br />

            <label>RR Tire Size:</label>
            <input type="text" name="tire_size_rr" /><br />
            <label>RR Pressure (PSI):</label>
            <input type="number" step="0.1" name="tire_pressure_rr" /><br />

            <label>Front Stagger:</label>
            <input type="number" step="0.1" name="front_stagger" required><br />

            <label>Rear Stagger:</label>
            <input type="number" step="0.1" name="rear_stagger" required><br />

            <h3>Corner Weights and Cross Weights</h3>
            <label>LF Weight:</label>
            <input type="number" step="0.1" name="weight_lf" required><br />

            <label>RF Weight:</label>
            <input type="number" step="0.1" name="weight_rf" required><br />

            <label>LR Weight:</label>
            <input type="number" step="0.1" name="weight_lr" required><br />

            <label>RR Weight:</label>
            <input type="number" step="0.1" name="weight_rr" required><br />    

            <label>Left Side Weight:</label>
            <input type="number" step="0.1" name="weight_left" required><br />

            <label>Cross Weight (%) = RF+LRx100:</label>
            <input type="number" step="0.1" name="cross_weight" required><br />

            <label>Total Weight (lbs):</label>
            <input type="number" step="0.1" name="total_weight" required><br /> 

            <h3>Notes</h3>
            <textarea name="notes" rows="4" cols="50"></textarea><br />

            <label>&nbsp;</label>
            <input type="submit" value="Save Setup"><br />

        </form>

        <p><a href="index.php">View Setup List</a></p>

    </main>

    <?php include('footer.php'); ?>

</body>
</html>

    
