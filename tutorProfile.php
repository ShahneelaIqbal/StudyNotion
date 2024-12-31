<?php
include 'dbConnection.php';
session_start();

if (isset($_GET['id'])) {
    $tutor_id = $_GET['id'];

    // Fetch tutor details
    $tutor_query = "SELECT id, username, email, created_at FROM users WHERE id = $tutor_id AND role = 'tutor'";
    $tutor_result = $conn->query($tutor_query);

    if ($tutor_result->num_rows > 0) {
        $tutor = $tutor_result->fetch_assoc();
    } else {
        echo "Tutor not found!";
        exit();
    }

    // Handle Review and Rating Submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'], $_POST['review'])) {
        $rating = $_POST['rating'];
        $review = $_POST['review'];
        $user_id = $_SESSION['user_id'];  // Logged-in student ID

        // Insert the rating and review into the database
        $insert_query = "INSERT INTO ratings_reviews (user_id, tutor_id, rating, review)
                         VALUES ('$user_id', '$tutor_id', '$rating', '$review')";
        if ($conn->query($insert_query) === TRUE) {
            echo "Review and rating submitted successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Fetch all ratings and reviews for this tutor
    $reviews_query = "SELECT users.username, ratings_reviews.rating, ratings_reviews.review, ratings_reviews.created_at 
                      FROM ratings_reviews
                      JOIN users ON ratings_reviews.user_id = users.id
                      WHERE tutor_id = $tutor_id";
    $reviews_result = $conn->query($reviews_query);
} else {
    echo "Invalid request!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Profile</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #1e90ff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #1c7ed6;
        }
        .tutor-profile {
        width: 60%;
        margin: 20px auto;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .tutor-profile h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .review-card {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .review-card strong {
            font-size: 18px;
        }

        .review-card p {
            margin: 5px 0;
        }

        .review-card small {
            color: #888;
        }

    </style>
</head>
<body>

<div class="tutor-profile">
    <h2>Tutor Profile: <?php echo $tutor['username']; ?></h2>
    <p>Email: <?php echo $tutor['email']; ?></p>
    <p>Joined on: <?php echo $tutor['created_at']; ?></p>

    <!-- Back to Dashboard Button -->
    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>

    <!-- Rating and Review Form -->
    <h3>Leave a Rating and Review</h3>
    <form action="tutorProfile.php?id=<?php echo $tutor_id; ?>" method="POST">
        <label for="rating">Rating (1-5):</label>
        <select name="rating" id="rating" required>
            <option value="">Select Rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br><br>
        <label for="review">Review:</label><br>
        <textarea name="review" id="review" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Submit Review</button>
    </form>

    <!-- Display existing reviews -->
    <h3>Reviews:</h3>
    <?php if ($reviews_result->num_rows > 0): ?>
        <?php while ($review = $reviews_result->fetch_assoc()): ?>
            <div class="review-card">
                <strong><?php echo $review['username']; ?></strong>
                <p>Rating: <?php echo $review['rating']; ?>/5</p>
                <p><?php echo $review['review']; ?></p>
                <p><small>Reviewed on: <?php echo $review['created_at']; ?></small></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</div>



</body>
</html>
