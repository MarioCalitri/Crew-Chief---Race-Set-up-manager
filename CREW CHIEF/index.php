<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
        header("Location: login_form.php");
        exit();
    }

    require("database.php");

    // Fetch quick stats or recent events for the home dashboard
    $queryRecentEvents = 'SELECT * FROM race_events ORDER BY eventDate DESC LIMIT 3';
    $statement = $db->prepare($queryRecentEvents);
    $statement->execute();
    $recentEvents = $statement->fetchAll();
    $statement->closeCursor();

    // check user ID in the session
    $user_id = $_SESSION['user_id'] ?? 0;

// get all setups (since setups table doesn't have a user_id column)
    $querySetups = 'SELECT * FROM setups';
    $statementSetups = $db->prepare($querySetups);
    $statementSetups->execute();
    $setups = $statementSetups->fetchAll();
    $statementSetups->closeCursor();

    // fetch all profiles
    $queryProfiles = 'SELECT * FROM profiles';
    $statementProfiles = $db->prepare($queryProfiles);
    $statementProfiles->execute();
    $profiles = $statementProfiles->fetchAll();
    $statementProfiles->closeCursor();

?>



<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Home</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include('header.php'); ?>

    <main>
    <div class="welcomeSection">
        <h1>Welcome to Crew Chief</h1>
        <p>Grassroots short-track oval racing setup management & pit logging.</p>
    </div>

        <p>Current Logged In User: <?php echo htmlspecialchars($_SESSION["user_name"] ?? $_SESSION["username"] ?? 'Guest'); ?></p>

        <form action="logout.php" method="get" style="display: inline;">
            <input type="submit" value="Logout" />
        </form>

        <div class="dashboardCardsLarge">

        <!-- Section 2: Race Events -->
        <div class="featureCard">
            <div class="featureIcon">
                <!-- Calendar / Flag Clipart Icon -->
                <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM9 12H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm-8 4H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2z"/></svg>
            </div>
            <div class="featureContent">
                <h2>Race Events</h2>
                <p>Manage tracks, event dates, and schedule details.</p>
                <div id="buttons">
                <label>&nbsp;</label>
                    <form action="race_events.php" method="get" style="display: inline;">
                        <input type="submit" value="VIEW RACE EVENTS" />
                    </form>
            </div>
            </div>
        </div>

                <!-- Section 1: Setups -->
        <div class="featureCard">
            <div class="featureIcon">
                <!-- Car / Wrench Clipart Icon -->
                <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.5 7.1 1 10.1 3 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
            </div>
            <div class="featureContent">
                <h2>Chassis Setups</h2>
                <p>Log tire sizes, pressures, stagger, and corner weights for upcoming race events.</p>
                <div id="buttons">
                <label>&nbsp;</label>
                    <form action="setups.php" method="get" style="display: inline;">
                        <input type="submit" value="VIEW SETUP SHEETS" />
                    </form>
                </div>
            </div>
        </div>


        <!-- Section 3: Checklists & Pit Logs -->
        <div class="featureCard">
            <div class="featureIcon">
                <!-- Clipboard Clipart Icon -->
                <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 16H5V5h2v2h10V5h2v14z"/></svg>
            </div>
            <div class="featureContent">
                <h2>Checklists & Pit Logs</h2>
                <p>Track pre-race preparation and record session adjustments.</p>
                <div id="buttons">
                <label>&nbsp;</label>
                    <form action="pit_logs.php" method="get" style="display: inline;">
                        <input type="submit" value="VIEW LISTS & PIT LOGS" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Events -->
    <div class="recentEventsSection">
        <h3>Upcoming & Recent Events</h3>
        <ul class="recentEventsList">
            <li><strong>Gold Rush 126</strong> at Flamboro Speedway (2026-06-30)</li>
            <li><strong>Don Biederman Memorial</strong> at Deleware Speedway (2026-06-22)</li>
        </ul>
    </div>
</main>

    <?php include('footer.php'); ?>

</body>
</html>