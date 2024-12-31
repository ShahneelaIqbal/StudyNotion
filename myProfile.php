<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch user profile data from the database
include 'dbConnection.php';
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Fetch additional user settings data from user_settings table
$settings_query = "SELECT * FROM user_settings WHERE user_id = $user_id";
$settings_result = $conn->query($settings_query);
$settings = $settings_result->fetch_assoc();

// Check if form is submitted to update the data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new values from the form
    $notebank_id = $_POST['notebank_id'];
    $school = $_POST['school'];
    $course = $_POST['course'];

    // Check if the user already has an entry in user_settings
    if ($settings) {
        // Update the existing entry
        $update_query = "UPDATE user_settings SET notebank_id = '$notebank_id', school = '$school', course = '$course' WHERE user_id = $user_id";
        if ($conn->query($update_query)) {
            $message = "Profile updated successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        // If no entry exists, insert a new record
        $insert_query = "INSERT INTO user_settings (user_id, notebank_id, school, course) VALUES ($user_id, '$notebank_id', '$school', '$course')";
        if ($conn->query($insert_query)) {
            $message = "Profile created successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-info p {
            margin: 5px;
            color: #555;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .form-container label {
            font-weight: 500;
            color: #333;
        }

        .form-container input[type="text"], 
        .form-container input[type="submit"] {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1em;
            width: 100%;
        }

        .form-container input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: #4CAF50;
            margin-top: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #4CAF50;
            text-decoration: none;
            font-size: 1em;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-info">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Role: <?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
    </div>

    <div class="form-container">
        <h3>Account Settings</h3>

        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>

            <label for="notebank_id">Notebank ID:</label>
            <input type="text" id="notebank_id" name="notebank_id" value="<?php echo isset($settings['notebank_id']) ? htmlspecialchars($settings['notebank_id']) : ''; ?>">

            <label for="school">School:</label>
            <input type="text" id="school" name="school" value="<?php echo isset($settings['school']) ? htmlspecialchars($settings['school']) : ''; ?>">

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" value="<?php echo isset($settings['course']) ? htmlspecialchars($settings['course']) : ''; ?>">

            <input type="submit" value="Save Changes">
        </form>
    </div>

    <a href="dashboard.php" class="back-link">Go to Dashboard</a>
</div>

</body>
</html>
