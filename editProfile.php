<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current profile data from the users table
include 'dbConnection.php';
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Fetch user settings data
$settings_query = "SELECT * FROM user_settings WHERE user_id = $user_id";
$settings_result = $conn->query($settings_query);
$settings = $settings_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new values from the form
    $notebank_id = $_POST['notebank_id'];
    $school = $_POST['school'];
    $course = $_POST['course'];

    // Update user settings in the database
    $update_query = "UPDATE user_settings SET notebank_id = '$notebank_id', school = '$school', course = '$course' WHERE user_id = $user_id";
    if ($conn->query($update_query)) {
        echo "Profile updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="post">
        <label for="notebank_id">Notebank ID:</label><br>
        <input type="text" id="notebank_id" name="notebank_id" value="<?php echo htmlspecialchars($settings['notebank_id']); ?>"><br><br>
        
        <label for="school">School:</label><br>
        <input type="text" id="school" name="school" value="<?php echo htmlspecialchars($settings['school']); ?>"><br><br>
        
        <label for="course">Course:</label><br>
        <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($settings['course']); ?>"><br><br>
        
        <input type="submit" value="Update Profile">
    </form>

    <a href="myProfile.php">Back to Profile</a>
</body>
</html>
