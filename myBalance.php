<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch balance data from the database
include 'dbConnection.php';
$query = "SELECT balance FROM users WHERE id = $user_id";
$result = $conn->query($query);
$balance = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Balance</title>
</head>
<body>
    <h1>Your Balance</h1>
    <p>Current balance: $<?php echo $balance['balance']; ?></p>
</body>
</html>
