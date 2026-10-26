<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require_once("database.php");

    // Handle adding a new note
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_note') {
        $title = filter_input(INPUT_POST, 'title');
        $category = filter_input(INPUT_POST, 'category');
        $content = filter_input(INPUT_POST, 'content');

        if ($title && $content) {
            $query = 'INSERT INTO notes (title, category, content) VALUES (:title, :category, :content)';
            $stmt = $db->prepare($query);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':category', $category ?? 'General');
            $stmt->bindValue(':content', $content);
            $stmt->execute();
            $stmt->closeCursor();

            header('Location: notes.php');
            exit();
        }
    }

    // Handle deleting a note
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_note') {
        $noteID = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
        if ($noteID) {
            $query = 'DELETE FROM notes WHERE noteID = :noteID';
            $stmt = $db->prepare($query);
            $stmt->bindValue(':noteID', $noteID);
            $stmt->execute();
            $stmt->closeCursor();

            header('Location: notes.php');
            exit();
        }
    }

    // Fetch all notes
    $queryNotes = 'SELECT * FROM notes ORDER BY created_at DESC';
    $stmt = $db->prepare($queryNotes);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Notes Log</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <h1>Note Logs</h1>

        <!-- Add Note Form -->
        <section class="event-form-section">
            <h2>Add New Note</h2>
            <form action="notes.php" method="post">
                <input type="hidden" name="action" value="add_note">

                <label>Title:</label>
                <input type="text" name="title" placeholder="e.g. Handling" required /><br />

                <label>Category:</label>
                <select name="category" style="padding: 8px; margin-bottom: 10px; background: #121212; color: #fff; border: 1px solid #333;">
                    <option value="General">General</option>
                    <option value="Setup">Setup</option>
                    <option value="Engine">Engine</option>
                    <option value="Tires">Tires</option>
                    <option value="Track Conditions">Track Conditions</option>
                </select><br />

                <label>Notes / Content:</label><br />
                <textarea name="content" rows="5" cols="50" required placeholder="Enter notes here..."></textarea><br />

                <label>&nbsp;</label>
                <input type="submit" value="Save Note" /><br />
            </form>
        </section>

        <hr />

        <!-- Existing Notes List -->
        <section class="event-list-section">
            <h2>Saved Notes</h2>
            <?php if (empty($notes)): ?>
                <p>No notes recorded yet.</p>
            <?php else: ?>
                <table border="1" cellpadding="8" style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Note Content</th>
                            <th>Action</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notes as $note): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($note['created_at']))); ?></td>
                                <td><strong><?php echo htmlspecialchars($note['category']); ?></strong></td>
                                <td><?php echo htmlspecialchars($note['title']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($note['content'])); ?></td>
                                <td>
                                    <form action="update_note_form.php" method="get" style="display:inline;">
                                        <input type="hidden" name="note_id" value="<?php echo $note['noteID']; ?>">
                                        <input type="submit" value="Edit">
                                    </form>
                                </td>
                                <td>
                                    <form action="notes.php" method="post" onsubmit="return confirm('Delete this note?');">
                                        <input type="hidden" name="action" value="delete_note">
                                        <input type="hidden" name="note_id" value="<?php echo $note['noteID']; ?>">
                                        <input type="submit" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <div id="buttons">
                <label>&nbsp;</label>
                <form action="index.php" method="get" style="display: inline;">
                    <input type="submit" value="DASHBOARD" />
                </form>
            </div>
    </main>

    <?php include('footer.php'); ?>

</body>
</html>