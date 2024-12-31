<?php
include 'dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $photo = $_FILES['photo'];

    if (empty($email) || empty($password) || empty($photo)) {
        $error_message = "All fields are required.";
    } else {
        // Validate and process uploaded photo
        $targetDir = "uploads/";
        $targetFile = $targetDir . time() . "_" . basename($photo['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Debugging log for file upload path
        error_log("Uploaded file name: " . $photo['name']);
        error_log("Uploaded file path: " . $targetFile);

        if ($photo['size'] > 5000000 || !in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            $error_message = "Invalid file. Upload a JPG, JPEG, or PNG file under 5MB.";
        } else {
            if (move_uploaded_file($photo['tmp_name'], $targetFile)) {
                // Log the file path being passed to the Python script
                error_log("File Path being passed to Python: " . $targetFile);

                // Perform face and ID verification (replace this with actual API or ML model call)
                $verificationResult = verifyFaceAndID($targetFile);
                if (!$verificationResult) {
                    $error_message = "Verification failed. Ensure the photo contains your face and ID.";
                } else {
                    // Save tutor to the database
                    $sql = "INSERT INTO tutors (email, password, photo_path, verification_status) VALUES (?, ?, ?, 'pending')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $email, $password, $targetFile);

                    if ($stmt->execute()) {
                        // Success, redirect to tutor dashboard
                        header("Location: tutor_dashboard.php");  // Redirect to dashboard page
                        exit;  // Make sure no further code is executed
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }
                }
            } else {
                $error_message = "Failed to upload the photo.";
            }
        }
    }
}

// Mock function to verify face and ID (replace with actual implementation)
function verifyFaceAndID($filePath) {
    // Log file path for debugging
    error_log("File Path: " . $filePath);

    // Call the Python script
    $command = escapeshellcmd("python verify_face_and_id.py " . escapeshellarg($filePath));
    
    // Log the command being executed
    error_log("Executing Python Command: " . $command);

    $output = shell_exec($command);

    // Log Python output for debugging
    error_log("Python Output: " . $output);

    // Check if the output is not null and is a valid JSON
    $result = json_decode($output, true);

    if ($result === null) {
        // Handle error if output is not valid JSON
        error_log("Error: Invalid JSON returned from Python script.");
        return false;
    }

    // Check for 'status' key in the result
    if (isset($result['status']) && $result['status'] === true) {
        return true;  // Return True based on verification
    } else {
        // Handle case where 'status' is not present or is false
        error_log("Verification failed: " . json_encode($result));
        return false;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Signup</title>
    <style>
        /* Styling similar to the signup page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-container {
            width: 90%;
            max-width: 600px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
        }

        .signup-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #1e90ff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background: #1e90ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: #1c7ed6;
        }

        .message {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }

        .message.success {
            color: green;
        }

        .footer-text {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .footer-text a {
            color: #1e90ff;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up as Tutor</h1>
        <?php if (!empty($error_message)): ?>
            <div class="message"><?= $error_message ?></div>
        <?php elseif (!empty($success_message)): ?>
            <div class="message success"><?= $success_message ?></div>
        <?php endif; ?>
        <form method="POST" action="signup_tutor.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="photo">Upload Photo with ID Card</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>
            <button type="submit" class="btn-primary">Sign Up</button>
        </form>
        <p class="footer-text">Already have an account? <a href="signin.php">Sign In</a></p>
        <p class="footer-text">Go to Tutor Dashboard skip this for now <a href="TutorDashboard.php">Tutor Dashboard</a></p>

    </div>
</body>
</html>
