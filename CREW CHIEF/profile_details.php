<?php
    session_start();

    require_once("database.php");

    // get profile_id
    $profile_id = filter_input(INPUT_POST, 'profile_id', FILTER_VALIDATE_INT);
    if (!$profile_id) {
        $profile_id = filter_input(INPUT_GET, 'profile_id', FILTER_VALIDATE_INT);
    }

    if (!$profile_id) {
        header("Location: profiles.php");
        exit();
    }

    // fetch profile details
    $queryProfile = 'SELECT * FROM profiles WHERE profileID = :profile_id';

    $statement = $db->prepare($queryProfile);
    $statement->bindValue(':profile_id', $profile_id);
    $statement->execute();
    $profile = $statement->fetch();
    $statement->closeCursor();
    
    if (!$profile) {
        echo "Profile not found.";
        exit();
    }

    // resolve 400px image filename
    $image_name = $profile['imageName'];
    $imageName_400 = 'placeholder_400.jpg';

    if (!empty($image_name)) {
        $dot_pos = strrpos($image_name, '.');
        if ($dot_pos !== false) {
            $base_name = substr($image_name, 0, $dot_pos);
            $ext = substr($image_name, $dot_pos);

            if (str_ends_with($base_name, '_100')) {
                $base_name = substr($base_name, 0, -4);
            }

            $candidate_400 = $base_name . '_400' . $ext;

            if (file_exists('images/' . $candidate_400)) {
                $imageName_400 = $candidate_400;
            } elseif (file_exists('images/' . $image_name)) {
                $imageName_400 = $image_name;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew Chief Driver Profile Details</title>
    <link rel="stylesheet" type="text/css" href="css/setup.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main class="profile-page">

        <div class="profile-card">

            <div class="profile-header">
                <div class="profile-photo">
                    <img
                        src="images/<?php echo htmlspecialchars($imageName_400); ?>" 
                        alt="<?php echo htmlspecialchars($profile['firstName'] . ' ' . $profile['lastName']); ?>"
                        onerror="this.src='images/placeholder_100.jpg';">
                </div>
                <div class="profile-title">
                    <h1>
                        <?php echo htmlspecialchars($profile['firstName']); ?>
                        <?php echo htmlspecialchars($profile['lastName']); ?>
                    </h1>
                    <span class="driver-badge">
                        <?php echo htmlspecialchars($profile['carType']); ?>
                    </span>
                    <p class="team-name">
                        <?php echo htmlspecialchars($profile['teamName']); ?>
                    </p>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-row">
                    <span class="label">First Name</span>
                    <span class="value"><?php echo htmlspecialchars($profile['firstName']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Last Name</span>
                    <span class="value"><?php echo htmlspecialchars($profile['lastName']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Team</span>
                    <span class="value"><?php echo htmlspecialchars($profile['teamName']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Email</span>
                    <span class="value"><?php echo htmlspecialchars($profile['emailAddress']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Car Type</span>
                    <span class="value"><?php echo htmlspecialchars($profile['carType']); ?></span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="profiles.php" class="btn-back">
                    Back to Driver Profiles
                </a>
            </div>
            <div class="profile-actions">
                <form action="update_profile_form.php" method="post" style="display: inline;">
                    <input type="hidden" name="profile_id" value="<?php echo htmlspecialchars($profile['profileID']); ?>">
                    <input type="submit" value="Edit" class="btn-back">
                </form>
            </div>

        </div>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>