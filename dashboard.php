<?php

session_start();

require_once "config/database.php";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
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

    <title>Student Dashboard</title>

</head>

<body>

<div class="container">

    <h1>Smart Classroom Feedback System</h1>

    <h2>Student Dashboard</h2>

    <div class="card">

        <h3>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["name"]); ?>! 👋
        </h3>

        <p>
            Email:
            <?php echo htmlspecialchars($_SESSION["email"]); ?>
        </p>

    </div>


    <div class="card-container">

        <div class="card">

            <h3>📝 Give Feedback</h3>

            <p>
                Share your classroom experience and
                help improve teaching quality.
            </p>

            <a href="feedback.php">
                <button>
                    Give Feedback
                </button>
            </a>

        </div>


        <div class="card">

            <h3>📋 My Feedback</h3>

            <p>
                View, edit or delete your submitted feedback.
            </p>

            <a href="my_feedback.php">
                <button>
                    My Feedback
                </button>
            </a>

        </div>

    </div>


    <div class="card">

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>

</body>

</html>