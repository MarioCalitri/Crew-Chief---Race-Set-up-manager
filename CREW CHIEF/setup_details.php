<?php
    require_once('database.php');

    // Get setup ID from POST or GET
    $setupID = filter_input(INPUT_POST, 'setup_id', FILTER_VALIDATE_INT);
    if (!$setupID) {
        $setupID = filter_input(INPUT_GET, 'setup_id', FILTER_VALIDATE_INT);
    }

    if ($setupID === NULL || $setupID === FALSE) {
        $error = "Invalid setup ID.";
        include('error.php');
        exit();
    }

    // Fetch complete setup record and joined event details
    $query = 'SELECT s.*, e.eventName, e.trackName, e.eventDate, e.notes AS eventNotes
              FROM setups s
              LEFT JOIN race_events e ON s.eventID = e.eventID
              WHERE s.setupID = :setupID';

    $statement = $db->prepare($query);
    $statement->bindValue(':setupID', $setupID);
    $statement->execute();
    $setup = $statement->fetch();
    $statement->closeCursor();

    if (!$setup) {
        $error = "Setup sheet not found.";
        include('error.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Setup Details</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Setup Sheet Details</h2>

        <section class="details-section">
            <h3>Event Information</h3>
            <p><strong>Event Name:</strong> <?php echo htmlspecialchars($setup['eventName'] ?? 'N/A'); ?></p>
            <p><strong>Track:</strong> <?php echo htmlspecialchars($setup['trackName'] ?? 'N/A'); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($setup['eventDate'] ?? 'N/A'); ?></p>
        </section>

        <section class="details-section">
            <h3>Tires & Stagger</h3>
            <table>
                <tr>
                    <th>Position</th>
                    <th>Tire Size</th>
                    <th>Cold Pressure (PSI)</th>
                </tr>
                <tr>
                    <td>Left Front (LF)</td>
                    <td><?php echo htmlspecialchars($setup['tireSizeLF'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($setup['tirePressureLF'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Right Front (RF)</td>
                    <td><?php echo htmlspecialchars($setup['tireSizeRF'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($setup['tirePressureRF'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Left Rear (LR)</td>
                    <td><?php echo htmlspecialchars($setup['tireSizeLR'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($setup['tirePressureLR'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Right Rear (RR)</td>
                    <td><?php echo htmlspecialchars($setup['tireSizeRR'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($setup['tirePressureRR'] ?? '-'); ?></td>
                </tr>
            </table>

            <p><strong>Front Stagger:</strong> <?php echo htmlspecialchars($setup['frontStagger'] ?? '-'); ?>"</p>
            <p><strong>Rear Stagger:</strong> <?php echo htmlspecialchars($setup['rearStagger'] ?? '-'); ?>"</p>
        </section>

<section class="details-section">
    <h3>Wheel Weights & Percentages</h3>
    <p><strong>Corner Weights:</strong></p>
    <ul>
        <li><strong>LF:</strong> <?php echo htmlspecialchars($setup['weightLF'] ?? '-'); ?> lbs</li>
        <li><strong>RF:</strong> <?php echo htmlspecialchars($setup['weightRF'] ?? '-'); ?> lbs</li>
        <li><strong>LR:</strong> <?php echo htmlspecialchars($setup['weightLR'] ?? '-'); ?> lbs</li>
        <li><strong>RR:</strong> <?php echo htmlspecialchars($setup['weightRR'] ?? '-'); ?> lbs</li>
    </ul>

    <p><strong>Left Weight:</strong> <?php echo htmlspecialchars($setup['leftWeight'] ?? '-'); ?>%</p>
    <p><strong>Cross Weight:</strong> <?php echo htmlspecialchars($setup['crossWeight'] ?? '-'); ?>%</p>

    <?php 
        // Calculate dynamic total fallback if totalWeight is NULL or empty
        $calcTotal = ($setup['weightLF'] ?? 0) + ($setup['weightRF'] ?? 0) + ($setup['weightLR'] ?? 0) + ($setup['weightRR'] ?? 0);
        $displayTotal = !empty($setup['totalWeight']) ? $setup['totalWeight'] : ($calcTotal > 0 ? $calcTotal : '-');
    ?>
    <p><strong>Total Weight:</strong> <?php echo htmlspecialchars($displayTotal); ?> lbs</p>
</section>
        <section class="details-section">
            <h3>Gearing & Notes</h3>
            <p><strong>Gear Ratio:</strong> <?php echo htmlspecialchars($setup['gearRatio'] ?? 'N/A'); ?></p>
            <p><strong>Setup Notes:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($setup['notes'] ?? 'No notes recorded.')); ?></p>
        </section>

        <p>
            <form action="update_setup_form.php" method="post" style="display:inline;">
                <input type="hidden" name="setup_id" value="<?php echo $setup['setupID']; ?>" />
                <input type="submit" value="Edit Setup" />
            </form> 
            <form action="setups.php" method="post" style="display:inline;">
                <input type="hidden" name="setup_id" value="<?php echo $setup['setupID']; ?>" />
                <input type="submit" value="Back to Setup List" />
            </form>
            
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>