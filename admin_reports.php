<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Faculty-wise report
$query = "
    SELECT
        faculty.name AS faculty_name,
        COUNT(feedback.id) AS total_feedback,
        AVG(feedback.teaching_rating) AS avg_teaching,
        AVG(feedback.clarity_rating) AS avg_clarity,
        AVG(feedback.interaction_rating) AS avg_interaction
    FROM faculty
    LEFT JOIN feedback
        ON faculty.id = feedback.faculty_id
    GROUP BY faculty.id
    ORDER BY faculty.name
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

    <title>Feedback Reports</title>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Smart Classroom Feedback System</h1>

        <h2>📊 Feedback Reports</h2>

        <p>
            Faculty-wise feedback performance report.
        </p>

    </div>


    <div class="card">

        <div class="table-container">

            <table>

                <tr>

                    <th>Faculty</th>

                    <th>Total Feedback</th>

                    <th>Teaching</th>

                    <th>Clarity</th>

                    <th>Interaction</th>

                    <th>Overall Average</th>

                </tr>


                <?php while ($row = $result->fetch_assoc()) { ?>

                    <?php

                    $avg_teaching =
                        $row["avg_teaching"] ?? 0;

                    $avg_clarity =
                        $row["avg_clarity"] ?? 0;

                    $avg_interaction =
                        $row["avg_interaction"] ?? 0;

                    $overall_average =
                        (
                            $avg_teaching +
                            $avg_clarity +
                            $avg_interaction
                        ) / 3;

                    ?>


                    <tr>

                        <td>

                            <strong>
                                👨‍🏫
                                <?php
                                echo htmlspecialchars(
                                    $row["faculty_name"]
                                );
                                ?>
                            </strong>

                        </td>


                        <td>

                            📋
                            <?php
                            echo $row["total_feedback"];
                            ?>

                        </td>


                        <td>

                            ⭐
                            <?php
                            echo number_format(
                                $avg_teaching,
                                2
                            );
                            ?>/5

                        </td>


                        <td>

                            ⭐
                            <?php
                            echo number_format(
                                $avg_clarity,
                                2
                            );
                            ?>/5

                        </td>


                        <td>

                            ⭐
                            <?php
                            echo number_format(
                                $avg_interaction,
                                2
                            );
                            ?>/5

                        </td>


                        <td>

                            <strong>

                                📊
                                <?php
                                echo number_format(
                                    $overall_average,
                                    2
                                );
                                ?>/5

                            </strong>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>


    <div class="card">

        <a href="admin_dashboard.php">

            <button>
                ← Back to Admin Dashboard
            </button>

        </a>

        <br><br>

        <a href="admin_feedback.php">
            📋 View All Feedback
        </a>

        <br><br>

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>

</body>

</html>