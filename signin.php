<?php
include 'dbConnection.php';
session_start();

// If the user is already logged in, redirect them to the dashboard
if (isset($_SESSION['user_id'])) {
    // Redirect based on user role (Tutor or Regular User)
    if ($_SESSION['role'] == 'student') {
        header('Location: dashboard.php'); // Redirect to student dashboard
    } else {
        header('Location: TutorDashboard.php'); // Redirect to tutor dashboard
    }
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo "<script>alert('Both email and password are required.');</script>";
    } else {
        // Query to fetch user details
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                // Store user data in the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email']; // Corrected line

                // Redirect based on user role (Tutor or Regular User)
                if ($_SESSION['role'] == 'tutor') {
                    header('Location: TutorDashboard.php'); // Redirect to tutor dashboard
                } else {
                    header('Location: dashboard.php'); // Redirect to student dashboard
                }
                exit();
            } else {
                echo "<script>alert('Invalid password.');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Study Pool</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f3f4f6; }
        .signup-container { display: flex; width: 80%; max-width: 900px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .left-section { flex: 1; background: linear-gradient(135deg, #1e90ff, #87ceeb); color: #ffffff; padding: 30px; }
        .left-section h1 { font-size: 24px; margin-bottom: 20px; }
        .left-section p { font-size: 16px; margin-bottom: 20px; }
        .right-section { flex: 1; padding: 30px; }
        .tabs { display: flex; justify-content: flex-start; margin-bottom: 20px; }
        .tabs span { font-size: 18px; margin-right: 20px; cursor: pointer; color: #666666; }
        .tabs .active-tab { color: #1e90ff; font-weight: bold; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; color: #333333; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 5px; font-size: 14px; }
        .btn-primary { width: 100%; padding: 10px; background: #1e90ff; color: #ffffff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px; }
        .btn-primary:hover { background: #1c7ed6; }
        .footer-text { text-align: center; margin-top: 15px; font-size: 14px; }
        .footer-text a { color: #1e90ff; text-decoration: none; }
        .footer-text a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="left-section">
            <h1>Welcome Back!</h1>
            <p>Sign in to access your personalized study resources and connect with verified tutors.</p>
        </div>
        <div class="right-section">
            <div class="tabs">
                <span>Sign Up</span>
                <span class="active-tab">Sign In</span>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-primary">Sign In</button>
            </form>
            <p class="footer-text">Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
