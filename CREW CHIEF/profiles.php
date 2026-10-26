<?php 
    session_start();
    require_once("database.php");

    // check user ID in the session
    $user_id = $_SESSION['user_id'] ?? 0;

    // get setups for the logged-in user only
    $querySetups = 'SELECT * FROM setups';
    $statementSetups = $db->prepare($querySetups);
    $statementSetups->execute();
    $setups = $statementSetups->fetchAll();
    $statementSetups->closeCursor();

    // fetch profiles for logged-in user only
    $queryProfiles = 'SELECT * FROM profiles';
    $statementProfiles = $db->prepare($queryProfiles);
    $statementProfiles->execute();
    $profiles = $statementProfiles->fetchAll();
    $statementProfiles->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CREW CHIEF PROFILE LOG</title>
    <link rel="stylesheet" type="text/css" href="css/setup.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Driver Profiles</h2>

            <p>Current Logged In User: <?php echo htmlspecialchars($_SESSION["user_name"] ?? $_SESSION["username"] ?? 'Guest'); ?></p>


        <table>
            <tr>
                <th>Image</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Team Name</th>
                <th>Email Address</th>
                <th>Car Type</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Details</th>
            </tr>

        <?php foreach ($profiles as $profile) :
            $emailVal = isset($profile['emailAddress']) ? $profile['emailAddress'] : (isset($profile['email']) ? $profile['email'] : '');
        ?>
            <tr>
                <td>
                    <?php if (!empty($profile['imageName'])) : ?>
                        <img src="images/<?php echo htmlspecialchars($profile['imageName']); ?>" 
                            alt="Profile" 
                            onerror="this.src='images/placeholder_100.jpg';">
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($profile['firstName']); ?></td>
                <td><?php echo htmlspecialchars($profile['lastName']); ?></td>
                <td><?php echo htmlspecialchars($profile['teamName']); ?></td>
                <td><?php echo htmlspecialchars($emailVal); ?></td>
                <td><?php echo htmlspecialchars($profile['carType']); ?></td>
                <td>
                    <form action="update_profile_form.php" method="post">
                        <input type="hidden" name="profile_id" value="<?php echo $profile['profileID']; ?>">
                        <input type="submit" value="EDIT" class="btn-action">
                    </form>
                </td>
                <td>
                    <form action="delete_profile.php" method="post">
                        <input type="hidden" name="profile_id" value="<?php echo $profile['profileID']; ?>">
                        <input type="submit" value="DELETE" class="btn-delete">
                    </form>
                </td>
                <td>
                    <form action="update_profile_form.php" method="post">
                        <input type="hidden" name="profile_id" value="<?php echo $profile['profileID']; ?>">
                        <a href="profile_details.php?profile_id=<?php echo $profile['profileID']; ?>" class="btn-action">DETAILS</a>                    
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        
        </table>

        <p><a href="add_profile_form.php" class="btn-add">Add New Driver Profile</a></p>
        <p><a href="index.php" class="btn-add">Dashboard</a></p>

    </main>

    <?php include("footer.php"); ?>
</body>
</html>