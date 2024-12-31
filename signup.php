<?php
// Include the database connection file
include 'dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form inputs
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);

    // Check if email or username is already registered
    $checkQuery = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Email or username already exists!";
    } else {
        // Hash the password before inserting into the database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user into the database
        $insertQuery = "INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $email, $username, $hashed_password, $role);

        if ($stmt->execute()) {
            if ($role === 'tutor') {
                // Redirect tutors to the additional credentials page
                header("Location: signup_tutor.php?username=" . urlencode($username));
                exit();
            } else {
                $success_message = "Signup successful!";
            }
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Study Pool</title>
    <style>
        /* Add your styling here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f3f4f6;
        }

        .signup-container {
            display: flex;
            width: 80%;
            max-width: 900px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .left-section {
            flex: 1;
            background: linear-gradient(135deg, #1e90ff, #87ceeb);
            color: #ffffff;
            padding: 30px;
        }

        .left-section h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .left-section ul {
            list-style: none;
        }

        .left-section ul li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .left-section ul li .icon {
            font-size: 20px;
            margin-right: 10px;
        }

        .right-section {
            flex: 1;
            padding: 30px;
        }

        .tabs {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .tabs span {
            font-size: 18px;
            margin-right: 20px;
            cursor: pointer;
            color: #666666;
        }

        .tabs .active-tab {
            color: #1e90ff;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background: #1e90ff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: #1c7ed6;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .footer-text a {
            color: #1e90ff;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 15px;
            color: red;
            text-align: center;
        }

        .message.success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="left-section">
            <h1>Take 10 seconds to Sign up!</h1>
            <ul>
                <li><i class="icon">&#10003;</i> Get on-demand Q&A study help from verified tutors</li>
                <li><i class="icon">&#10003;</i> Access over 35 million study documents through the notebook</li>
                <li><i class="icon">&#10003;</i> Read 1000s of rich book guides covering popular titles</li>
            </ul>
        </div>
        <div class="right-section">
            <div class="tabs">
                <span class="active-tab">Sign Up</span>
                <span><a href="signin.php" style="text-decoration: none; color: #666;">Login</a></span>
            </div>
            <form method="post" action="">
                <?php
                if (isset($error_message)) {
                    echo "<div class='message'>$error_message</div>";
                } elseif (isset($success_message)) {
                    echo "<div class='message success'>$success_message</div>";
                }
                ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="student">Student</option>
                        <option value="tutor">Tutor</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Create Account</button>
            </form>
            <p class="footer-text">Have an account? <a href="signin.php">Login</a></p>
        </div>
    </div>
</body>
</html>
