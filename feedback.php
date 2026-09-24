<?php

session_start();

require_once "config/database.php";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Get faculty
$faculty_query = "
    SELECT id, name, department
    FROM faculty
    ORDER BY name
";

$faculty_result = $conn->query($faculty_query);


// Get subjects
$subject_query = "
    SELECT id, subject_name, faculty_id
    FROM subjects
    ORDER BY subject_name
";

$subject_result = $conn->query($subject_query);


// Submit feedback
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_SESSION["user_id"];

    $faculty_id = intval($_POST["faculty_id"]);
    $subject_id = intval($_POST["subject_id"]);

    $teaching_rating = intval($_POST["teaching_rating"]);
    $clarity_rating = intval($_POST["clarity_rating"]);
    $interaction_rating = intval($_POST["interaction_rating"]);

    $comment = trim($_POST["comment"]);


    // Validation
    if (
        $faculty_id <= 0 ||
        $subject_id <= 0 ||
        $teaching_rating < 1 ||
        $teaching_rating > 5 ||
        $clarity_rating < 1 ||
        $clarity_rating > 5 ||
        $interaction_rating < 1 ||
        $interaction_rating > 5
    ) {

        $message = "Please fill all required fields correctly.";

    } else {

        // Insert feedback
        $stmt = $conn->prepare(
            "INSERT INTO feedback
            (
                student_id,
                faculty_id,
                subject_id,
                teaching_rating,
                clarity_rating,
                interaction_rating,
                comment
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iiiiiis",
            $student_id,
            $faculty_id,
            $subject_id,
            $teaching_rating,
            $clarity_rating,
            $interaction_rating,
            $comment
        );


        if ($stmt->execute()) {

            $message = "Feedback submitted successfully!";

        } else {

            $message = "Failed to submit feedback.";

        }

        $stmt->close();
    }
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

    <title>Give Feedback</title>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Smart Classroom Feedback System</h1>

        <h2>📝 Give Feedback</h2>

        <p>
            Share your classroom experience.
        </p>


        <?php if (!empty($message)) { ?>

            <p>
                <strong>
                    <?php echo htmlspecialchars($message); ?>
                </strong>
            </p>

        <?php } ?>


        <form method="POST">

            <!-- Faculty -->

            <label>Faculty:</label>

            <select name="faculty_id" required>

                <option value="">
                    Select Faculty
                </option>

                <?php while ($faculty = $faculty_result->fetch_assoc()) { ?>

                    <option value="<?php echo $faculty["id"]; ?>">

                        <?php
                        echo htmlspecialchars(
                            $faculty["name"]
                        );
                        ?>

                        -

                        <?php
                        echo htmlspecialchars(
                            $faculty["department"]
                        );
                        ?>

                    </option>

                <?php } ?>

            </select>

            <br><br>


            <!-- Subject -->

            <label>Subject:</label>

            <select name="subject_id" required>

                <option value="">
                    Select Subject
                </option>

                <?php while ($subject = $subject_result->fetch_assoc()) { ?>

                    <option value="<?php echo $subject["id"]; ?>">

                        <?php
                        echo htmlspecialchars(
                            $subject["subject_name"]
                        );
                        ?>

                    </option>

                <?php } ?>

            </select>

            <br><br>


            <!-- Teaching -->

            <label>Teaching Quality:</label>

            <select name="teaching_rating" required>

                <option value="">
                    Select Rating
                </option>

                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>

            </select>

            <br><br>


            <!-- Clarity -->

            <label>Clarity of Explanation:</label>

            <select name="clarity_rating" required>

                <option value="">
                    Select Rating
                </option>

                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>

            </select>

            <br><br>


            <!-- Interaction -->

            <label>Faculty Interaction:</label>

            <select name="interaction_rating" required>

                <option value="">
                    Select Rating
                </option>

                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>

            </select>

            <br><br>


            <!-- Comment -->

            <label>Comment:</label>

            <textarea
                name="comment"
                rows="5"
                placeholder="Write your feedback..."
            ></textarea>

            <br><br>

            <button type="submit">
                Submit Feedback
            </button>

        </form>


        <br>

        <a href="dashboard.php">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>