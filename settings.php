<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch user data from the database
include 'dbConnection.php';
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Update functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $email = $_POST['email'];

    // Verify the old password
    if (password_verify($old_password, $user['password'])) {
        // Update password and email
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET email = '$email', password = '$hashed_new_password' WHERE id = $user_id";
        
        if ($conn->query($update_query)) {
            $message = "Settings updated successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Incorrect old password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5em;
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container label {
            font-weight: 500;
            color: #333;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="submit"] {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1em;
            width: 100%;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="email"]:focus,
        .form-container input[type="password"]:focus {
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
    <h1>Update Settings</h1>

    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

    <div class="form-container">
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="old_password">Old Password:</label>
            <input type="password" name="old_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>

            <input type="submit" value="Update Settings">
        </form>
    </div>

    <a href="dashboard.php" class="back-link">Go to Dashboard</a>
</div>

</body>
</html>
