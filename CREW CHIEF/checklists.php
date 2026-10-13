<?php
    require_once("database.php");

    // Fetch lists joined with event details
    $queryLists = 'SELECT l.listID, l.listTitle, l.category, e.eventName, e.trackName
                   FROM lists l
                   LEFT JOIN race_events e ON l.eventID = e.eventID
                   ORDER BY l.listID DESC';
    $statement = $db->prepare($queryLists);
    $statement->execute();
    $lists = $statement->fetchAll();
    $statement->closeCursor();

    // Fetch all list items
    $queryItems = 'SELECT * FROM list_items ORDER BY itemID ASC';
    $statement = $db->prepare($queryItems);
    $statement->execute();
    $allItems = $statement->fetchAll();
    $statement->closeCursor();

    // Group items by listID
    $listItems = [];
    foreach ($allItems as $item) {
        $listItems[$item['listID']][] = $item;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Pre-Race Checklists</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Pre-Race Checklists & Pit Lane Tasks</h2>

        <p><a href="add_checklist_form.php">Create New Checklist</a> | <a href="index.php">Back to Setup Sheets</a></p>

        <?php if (empty($lists)): ?>
            <p>No checklists created yet.</p>
        <?php else: ?>
            <?php foreach ($lists as $list): ?>
                <section class="details-section" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
                    <h3><?php echo htmlspecialchars($list['listTitle']); ?> 
                        <small>(Category: <?php echo htmlspecialchars($list['category']); ?> - Event: <?php echo htmlspecialchars($list['eventName'] ?? 'General'); ?>)</small>
                    </h3>

                    <ul style="list-style-type: none; padding-left: 0;">
                        <?php 
                            $items = $listItems[$list['listID']] ?? [];
                            if (empty($items)): 
                        ?>
                            <li><em>No checklist items added yet.</em></li>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <li style="margin-bottom: 8px;">
                                    <form action="toggle_item.php" method="post" style="display:inline;">
                                        <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                                        <input type="hidden" name="current_status" value="<?php echo $item['isCompleted']; ?>" />
                                        <input type="checkbox" onchange="this.form.submit()" <?php echo $item['isCompleted'] ? 'checked' : ''; ?> />
                                    </form>

                                    <span style="<?php echo $item['isCompleted'] ? 'text-decoration: line-through; color: #888;' : 'font-weight: bold;'; ?>">
                                        <?php echo htmlspecialchars($item['itemName']); ?> 
                                        (Qty: <?php echo htmlspecialchars($item['quantity']); ?>)
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <!-- Inline Form to Add New Item to this List -->
                    <form action="add_list_item.php" method="post" style="margin-top: 10px;">
                        <input type="hidden" name="list_id" value="<?php echo $list['listID']; ?>" />
                        <input type="text" name="item_name" placeholder="New task/item name" required />
                        <input type="number" name="quantity" value="1" min="1" style="width: 60px;" required />
                        <input type="submit" value="Add Item" />
                    </form>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>