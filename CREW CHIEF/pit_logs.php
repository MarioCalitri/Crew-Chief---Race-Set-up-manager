<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require("database.php");

    $queryLogs = 'SELECT l.*, e.eventName, e.trackName 
                  FROM pit_logs l
                  LEFT JOIN race_events e ON l.eventID = e.eventID
                  ORDER BY l.logDate DESC';
    $statement = $db->prepare($queryLogs);
    $statement->execute();
    $pitLogs = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Pit Logs</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>
    <?php include('header.php'); ?>

    <main class="pitLogListSection">
    <h1>Pit Logs & Session Adjustments</h1>

    <table class="pitLogTable">
        <thead>
            <tr>
                <th style="width: 20%;">EVENT / TRACK</th>
                <th style="width: 12%;">SESSION</th>
                <th style="width: 24%;">HOT PRESSURES<br><small>(LF/RF/LR/RR)</small></th>
                <th style="width: 28%;">ADJUSTMENTS</th>
                <th style="width: 8%; text-align: center;">EDIT</th>
                <th style="width: 8%; text-align: center;">DELETE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pitLogs as $log): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($log['eventName']); ?></strong><br>
                        <span style="color: #888; font-size: 0.85rem;"><?php echo htmlspecialchars($log['trackName']); ?></span>
                    </td>
                    <td><strong><?php echo htmlspecialchars($log['sessionName']); ?></strong></td>
                    <th class="alignLeft">HOT PRESSURES<br><small class="subText">(LF/RF/LR/RR)</small></th>
                    <td class="alignLeft pressureValues">
                        <?php echo htmlspecialchars($log['hotPressureLF'] ?? '-'); ?> / 
                        <?php echo htmlspecialchars($log['hotPressureRF'] ?? '-'); ?> / 
                        <?php echo htmlspecialchars($log['hotPressureLR'] ?? '-'); ?> / 
                        <?php echo htmlspecialchars($log['hotPressureRR'] ?? '-'); ?> PSI
                    </td>
                    <td><?php echo nl2br(htmlspecialchars($log['adjustments'] ?? 'None recorded')); ?></td>
                    <td class="actionCell">
                        <a href="update_pit_log_form.php?log_id=<?php echo $log['logID']; ?>" class="btnTableEdit">Update</a>
                    </td>
                    <td class="actionCell">
                            <form action="delete_pit_log.php" method="post" style="margin: 0;">
                                <input type="hidden" name="log_id" value="<?php echo htmlspecialchars($log['logID']); ?>">
                                <input type="submit" value="Delete" class="btnTableDelete" onclick="return confirm('Delete this pit log?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 25px;">
            <a href="add_pit_log_form.php" class="btnFeature">Add New Pit Log</a>
        </div>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>