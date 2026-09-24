<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}


// Total students
$student_query = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'student'"
);

$total_students = $student_query->fetch_assoc()["total"];


// Total feedback
$feedback_query = $conn->query(
    "SELECT COUNT(*) AS total
     FROM feedback"
);

$total_feedback = $feedback_query->fetch_assoc()["total"];


// Average rating
$rating_query = $conn->query(
    "SELECT AVG(teaching_rating) AS average
     FROM feedback"
);

$average_rating = $rating_query->fetch_assoc()["average"];

if ($average_rating === null) {
    $average_rating = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link rel="stylesheet" href="css/style.css">

    <title>Admin Dashboard</title>

</head>

<body>

<div class="container">

    <h1>Smart Classroom Feedback System</h1>

    <h2>Admin Dashboard</h2>

    <p>
        Welcome,
        <strong>
            <?php echo htmlspecialchars($_SESSION["name"]); ?>
        </strong>
    </p>


    <!-- Statistics -->

    <div class="card-container">

        <div class="card">

            <h3>Total Students</h3>

            <h2>
                <?php echo $total_students; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Feedback</h3>

            <h2>
                <?php echo $total_feedback; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Average Rating</h3>

            <h2>
                <?php echo number_format($average_rating, 2); ?>/5
            </h2>

        </div>

    </div>


    <!-- Admin Options -->

    <div class="card">

        <h3>Admin Options</h3>

        <p>
            <a href="admin_feedback.php">
                📋 View All Feedback
            </a>
        </p>

        <p>
            <a href="admin_reports.php">
                📊 View Reports
            </a>
        </p>

        <p>
            <a href="logout.php">
                🚪 Logout
            </a>
        </p>

    </div>

</div>

</body>

</html>