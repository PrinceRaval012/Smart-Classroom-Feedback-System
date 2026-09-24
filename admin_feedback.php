<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Get all feedback
$query = "
    SELECT
        feedback.id,
        users.name AS student_name,
        users.email AS student_email,
        faculty.name AS faculty_name,
        subjects.subject_name,
        feedback.teaching_rating,
        feedback.clarity_rating,
        feedback.interaction_rating,
        feedback.comment,
        feedback.created_at
    FROM feedback
    INNER JOIN users
        ON feedback.student_id = users.id
    INNER JOIN faculty
        ON feedback.faculty_id = faculty.id
    INNER JOIN subjects
        ON feedback.subject_id = subjects.id
    ORDER BY feedback.created_at DESC
";

$result = $conn->query($query);

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

    <title>All Feedback</title>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Smart Classroom Feedback System</h1>

        <h2>📋 All Student Feedback</h2>

        <p>
            Admin can view all submitted student feedback here.
        </p>

    </div>


    <?php if ($result->num_rows > 0) { ?>

        <div class="card">

            <div class="table-container">

                <table>

                    <tr>

                        <th>Student</th>

                        <th>Email</th>

                        <th>Faculty</th>

                        <th>Subject</th>

                        <th>Teaching</th>

                        <th>Clarity</th>

                        <th>Interaction</th>

                        <th>Comment</th>

                        <th>Date</th>

                    </tr>


                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["student_name"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["student_email"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["faculty_name"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["subject_name"]
                                );
                                ?>
                            </td>


                            <td>
                                ⭐
                                <?php
                                echo $row["teaching_rating"];
                                ?>/5
                            </td>


                            <td>
                                ⭐
                                <?php
                                echo $row["clarity_rating"];
                                ?>/5
                            </td>


                            <td>
                                ⭐
                                <?php
                                echo $row["interaction_rating"];
                                ?>/5
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["comment"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["created_at"]
                                );
                                ?>
                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>

    <?php } else { ?>

        <div class="card">

            <h3>📭 No Feedback Available</h3>

            <p>
                No student feedback has been submitted yet.
            </p>

        </div>

    <?php } ?>


    <div class="card">

        <a href="admin_dashboard.php">
            <button>
                ← Back to Admin Dashboard
            </button>
        </a>

        <br><br>

        <a href="admin_reports.php">
            📊 View Reports
        </a>

        <br><br>

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>

</body>

</html>