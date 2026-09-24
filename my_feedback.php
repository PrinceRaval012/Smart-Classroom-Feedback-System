<?php

session_start();

require_once "config/database.php";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["user_id"];

// Get student's feedback
$stmt = $conn->prepare(
    "SELECT 
        feedback.id,
        faculty.name AS faculty_name,
        subjects.subject_name,
        feedback.teaching_rating,
        feedback.clarity_rating,
        feedback.interaction_rating,
        feedback.comment,
        feedback.created_at
    FROM feedback
    INNER JOIN faculty
        ON feedback.faculty_id = faculty.id
    INNER JOIN subjects
        ON feedback.subject_id = subjects.id
    WHERE feedback.student_id = ?
    ORDER BY feedback.created_at DESC"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

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

    <title>My Feedback</title>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Smart Classroom Feedback System</h1>

        <h2>📋 My Feedback</h2>

        <p>
            Here you can view, edit and delete your submitted feedback.
        </p>

    </div>


    <?php if ($result->num_rows > 0) { ?>

        <div class="card">

            <div class="table-container">

                <table>

                    <tr>

                        <th>Faculty</th>

                        <th>Subject</th>

                        <th>Teaching</th>

                        <th>Clarity</th>

                        <th>Interaction</th>

                        <th>Comment</th>

                        <th>Date</th>

                        <th>Actions</th>

                    </tr>


                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>

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


                            <td>

                                <a
                                    href="edit_feedback.php?id=<?php echo $row['id']; ?>"
                                >
                                    ✏️ Edit
                                </a>

                                <br><br>

                                <a
                                    href="delete_feedback.php?id=<?php echo $row['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this feedback?');"
                                >
                                    🗑️ Delete
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>

    <?php } else { ?>

        <div class="card">

            <h3>📭 No Feedback Found</h3>

            <p>
                You have not submitted any feedback yet.
            </p>

        </div>

    <?php } ?>


    <div class="card">

        <a href="feedback.php">
            <button>
                📝 Give New Feedback
            </button>
        </a>

        <br><br>

        <a href="dashboard.php">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>