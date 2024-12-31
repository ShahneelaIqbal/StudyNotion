<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Bank</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #f8f9fa, #e0e7ff);
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #007bff;
        }

        .step {
            background: #ffffff;
            border-radius: 8px;
            padding: 15px;
            margin: 15px auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        .step h3 {
            color: #007bff;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #007bff;
            padding: 10px 20px;
            align-items: center;
        }

        .navbar img {
            height: 50px;
        }

        .navbar input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .menu-icon {
            display: none;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        .menu {
            display: flex;
            gap: 20px;
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        .menu a:hover {
            color: #ffe600;
        }

        .content {
            padding: 20px;
        }

        .content .step p {
            font-size: 16px;
            line-height: 1.6;
        }

        .content .step:nth-child(odd) {
            background-color: #f0f8ff;
        }

        @media (max-width: 768px) {
            .menu {
                display: none;
                flex-direction: column;
                background-color: #007bff;
                position: absolute;
                top: 60px;
                right: 0;
                width: 100%;
            }

            .menu-icon {
                display: block;
            }

            .menu.show {
                display: flex;
            }
        }

        .background {
            background-image: url('https://images.unsplash.com/photo-1557683304-673a23048d34?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fHN0dWR5fGVufDB8fHx8MTY4MjU2NTY0Mw&ixlib=rb-1.2.1&q=80&w=1080');
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            opacity: 0.3;
        }

    </style>
    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            menu.classList.toggle('show');
        }
    </script>
</head>
<body>
    <div class="background"></div>

    <!-- Navbar -->
    <div class="navbar">
        <a href="dashboard.php">
            <img src="https://www.studypool.com/images/logo.png" width="150px" alt="Study Pool Logo">
        </a>
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="menu">
            <a href="myProfile.php">My Profile</a>
            <a href="postQuestion.php">Post a Question</a>
            <a href="noteBank.php">Notebank</a>
            <a href="bookGuides.php">Book Guides</a>
            <a href="howItWorks.php">How It Works</a>
            <a href="myTutors.php">Tutors</a>
            <a href="settings.php">Settings</a>
            <a href="signout.php">Logout</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>How It Works</h1>

        <div class="step">
            <h3>1. Sign Up or Log In</h3>
            <p>Create an account to get started. Students and tutors can easily sign up by providing their email addresses and creating secure passwords. Tutors can also set up their profiles with details such as qualifications, subjects of expertise, and hourly rates, while students can add details like their academic interests and learning goals.</p>
        </div>

        <div class="step">
            <h3>2. Post a Question</h3>
            <p>Students can post questions in any subject, specifying detailed requirements such as topics, deadlines, file attachments, and budget constraints. This ensures that tutors clearly understand the student's needs and can respond with appropriate bids.</p>
        </div>

        <div class="step">
            <h3>3. Select a Tutor</h3>
            <p>After posting a question, students will receive multiple bids from tutors. They can view tutor profiles, reviews, and ratings to make an informed decision before selecting the tutor who best meets their academic and budgetary needs.

</p>
        </div>

        <div class="step">
            <h3>4. Real-Time Communication</h3>
            <p>To make the process smooth, students and tutors can interact via a built-in real-time chat system. This feature allows them to discuss requirements, share additional resources, and clarify any questions to ensure accurate and satisfactory solutions.</p>
        </div>

        <div class="step">
            <h3>5. Complete and Submit</h3>
            <p>Tutors work on the questions and submit their solutions directly on the platform. Students can review the submitted work, provide feedback, and request revisions if needed before marking the task as completed.</p>
        </div>

        <div class="step">
            <h3>6. Payment and Reviews</h3>
            <p>Payments are securely handled through the platform using trusted payment gateways. Once the task is marked as completed, students can pay the agreed amount. Additionally, both students and tutors can leave reviews and ratings for each other, helping to build trust and reputation within the community.</p>
        </div>

        <div class="step">
            <h3>7. Grow and Learn</h3>
            <p>Students can continue to expand their knowledge by accessing a wide variety of questions, resources, and study guides available in the platform’s repository. Tutors can grow their careers by gaining consistent work opportunities, enhancing their skills, and building a strong profile with positive reviews.</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Study Pool. All rights reserved.</p>
    </div>

</body>
</html>
