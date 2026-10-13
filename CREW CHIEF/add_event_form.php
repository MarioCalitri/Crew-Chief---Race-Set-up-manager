<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Add Race Event</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Add Race Event</h2>

        <form action="add_event.php" method="post" id="add_event_form">

            <label>Event Name:</label>
            <input type="text" name="event_name" required /><br />

            <label>Track Name:</label>
            <input type="text" name="track_name" required /><br />

            <label>Event Date:</label>
            <input type="date" name="event_date" required /><br />

            <label>Notes:</label>
            <textarea name="notes" rows="4" cols="50"></textarea><br />

            <label>&nbsp;</label>
            <input type="submit" value="Save Event" /><br />

        </form>

        <p><a href="events_list.php">View All Events</a></p>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>