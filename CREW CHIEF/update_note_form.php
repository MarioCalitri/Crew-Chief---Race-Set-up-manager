<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require_once("database.php");

    // Safely fetch note ID from GET request
    $noteID = isset($_GET['note_id']) ? filter_var($_GET['note_id'], FILTER_VALIDATE_INT) : null;

    if (!$noteID) {
        header('Location: notes.php');
        exit();
    }

    // Fetch the existing note data
    $query = 'SELECT * FROM notes WHERE noteID = :noteID';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':noteID', $noteID, PDO::PARAM_INT);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$note) {
        header('Location: notes.php');
        exit();
    }

    $categories = ['General', 'Setup', 'Engine', 'Tires', 'Track Conditions'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Edit Note</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <h2>Edit Note</h2>

        <form action="update_note.php" method="post">
            <input type="hidden" name="note_id" value="<?php echo htmlspecialchars($note['noteID']); ?>">

            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required /><br />

            <label>Category:</label>
            <select name="category" style="padding: 8px; margin-bottom: 10px; background: #121212; color: #fff; border: 1px solid #333;">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo ($note['category'] === $cat) ? 'selected' : ''; ?>>
                        <?php echo $cat; ?>
                    </option>
                <?php endforeach; ?>
            </select><br />

            <h3>Note Content</h3>
            <textarea name="content" rows="6" cols="50" required><?php echo htmlspecialchars($note['content']); ?></textarea><br />

            <label>&nbsp;</label>
            <input type="submit" value="Update Note" /><br />
        </form>

        <div id="buttons">
                <label>&nbsp;</label>
                <form action="notes.php" method="get" style="display: inline;">
                    <input type="submit" value="Back to Notes" />
                </form>
            </div>
    </main>

    <?php include('footer.php'); ?>

</body>
</html>