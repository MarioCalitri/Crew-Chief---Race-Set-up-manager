<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require("database.php");

    $querySetups = 'SELECT s.setupID, s.eventID, e.eventName, e.trackName, e.eventDate,
        s.tireSizeLF, s.tireSizeRF, s.tireSizeLR, s.tireSizeRR,
        s.tirePressureLF, s.tirePressureRF, s.tirePressureLR, s.tirePressureRR,
        s.frontStagger, s.rearStagger, s.crossWeight, s.totalWeight
        FROM setups s
        LEFT JOIN race_events e ON s.eventID = e.eventID
        ORDER BY e.eventDate DESC';

    $statement = $db->prepare($querySetups);
    $statement->execute();
    $setups = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>

<html>

    <head>
        <title>Crew Chief - Setup Sheets</title>
        <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
    </head>

    <body>

        <?php include('header.php'); ?>

        <h1>ChassisSetup Sheets</h1>

        <table>

                <tr>
                    <th>Event Name</th>
                    <th>Track Name</th>
                    <th>Event Date</th>
                    <th>Tire Sizes (LF/RF/LR/RR)</th>
                    <th>Pressures (LF/RF/LR/RR)</th>
                    <th>Front Stagger</th>
                    <th>Rear Stagger</th>
                    <th>Cross Wt (%)</th>
                    <th>Total Wt (lbs)</th>
                    <th>&nbsp;</th> <!-- for update -->
                    <th>&nbsp;</th> <!-- for delete -->
                    <th>&nbsp;</th> <!-- for view details -->
                </tr>

                <?php foreach ($setups as $setup) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($setup['eventName']); ?></td>
                        <td><?php echo htmlspecialchars($setup['trackName']); ?></td>
                        <td><?php echo htmlspecialchars($setup['eventDate']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($setup['tireSizeLF'] ?? '-'); ?> / 
                            <?php echo htmlspecialchars($setup['tireSizeRF'] ?? '-'); ?> / 
                            <?php echo htmlspecialchars($setup['tireSizeLR'] ?? '-'); ?> / 
                            <?php echo htmlspecialchars($setup['tireSizeRR'] ?? '-'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($setup['tirePressureLF']); ?>
                            <?php echo htmlspecialchars($setup['tirePressureRF']); ?>
                            <?php echo htmlspecialchars($setup['tirePressureLR']); ?>
                            <?php echo htmlspecialchars($setup['tirePressureRR']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($setup['frontStagger']); ?></td>
                        <td><?php echo htmlspecialchars($setup['rearStagger']); ?></td>
                        <td><?php echo htmlspecialchars($setup['crossWeight']); ?></td>
                        <td><?php echo htmlspecialchars($setup['totalWeight']); ?></td>

                        <td>
                            <form action="update_setup.php" method="post">
                                <input type="hidden" name="setup_id" value="<?php echo $setup['setupID']; ?>">
                                <input type="submit" value="Update">
                            </form>
                        </td>

                        <td>
                            <form action="delete_setup.php" method="post">
                                <input type="hidden" name="setup_id" value="<?php echo $setup['setupID']; ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>

                        <td>
                            <form action="setup_details.php" method="post">
                                <input type="hidden" name="setup_id" value="<?php echo $setup['setupID']; ?>">
                                <input type="submit" value="View Details">
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>

            </table>
            <p><a href="add_setup_form.php">Add New Setup</a></p>
                </main>

                <?php include("footer.php"); ?>
</body>
</html>
