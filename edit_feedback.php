<?php

session_start();

require_once "config/database.php";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["user_id"];

// Check feedback ID
if (!isset($_GET["id"])) {
    header("Location: my_feedback.php");
    exit();
}

$feedback_id = intval($_GET["id"]);

// Get feedback
$stmt = $conn->prepare(
    "SELECT *
     FROM feedback
     WHERE id = ? AND student_id = ?"
);

$stmt->bind_param("ii", $feedback_id, $student_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Feedback not found.");
}

$feedback = $result->fetch_assoc();

$stmt->close();

$message = "";

// Update feedback
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $teaching_rating = intval($_POST["teaching_rating"]);
    $clarity_rating = intval($_POST["clarity_rating"]);
    $interaction_rating = intval($_POST["interaction_rating"]);
    $comment = trim($_POST["comment"]);

    // Validation
    if (
        $teaching_rating < 1 || $teaching_rating > 5 ||
        $clarity_rating < 1 || $clarity_rating > 5 ||
        $interaction_rating < 1 || $interaction_rating > 5
    ) {

        $message = "Please select valid ratings.";

    } else {

        $stmt = $conn->prepare(
            "UPDATE feedback
             SET teaching_rating = ?,
                 clarity_rating = ?,
                 interaction_rating = ?,
                 comment = ?
             WHERE id = ? AND student_id = ?"
        );

        $stmt->bind_param(
            "iiisii",
            $teaching_rating,
            $clarity_rating,
            $interaction_rating,
            $comment,
            $feedback_id,
            $student_id
        );

        if ($stmt->execute()) {

            header("Location: my_feedback.php");
            exit();

        } else {

            $message = "Failed to update feedback.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Feedback</title>

</head>

<body>

    <h1>Smart Classroom Feedback System</h1>

    <h2>Edit Feedback</h2>

    <?php

    if (!empty($message)) {
        echo "<p>$message</p>";
    }

    ?>

    <p>
        Faculty:
        <?php echo htmlspecialchars($feedback["faculty_id"]); ?>
    </p>

    <p>
        Subject:
        <?php echo htmlspecialchars($feedback["subject_id"]); ?>
    </p>

    <form method="POST">

        <label>Teaching Quality:</label>
        <br>

        <select name="teaching_rating" required>

            <option value="1" <?php if ($feedback["teaching_rating"] == 1) echo "selected"; ?>>
                1 - Poor
            </option>

            <option value="2" <?php if ($feedback["teaching_rating"] == 2) echo "selected"; ?>>
                2 - Fair
            </option>

            <option value="3" <?php if ($feedback["teaching_rating"] == 3) echo "selected"; ?>>
                3 - Good
            </option>

            <option value="4" <?php if ($feedback["teaching_rating"] == 4) echo "selected"; ?>>
                4 - Very Good
            </option>

            <option value="5" <?php if ($feedback["teaching_rating"] == 5) echo "selected"; ?>>
                5 - Excellent
            </option>

        </select>

        <br><br>


        <label>Clarity of Explanation:</label>
        <br>

        <select name="clarity_rating" required>

            <option value="1" <?php if ($feedback["clarity_rating"] == 1) echo "selected"; ?>>
                1 - Poor
            </option>

            <option value="2" <?php if ($feedback["clarity_rating"] == 2) echo "selected"; ?>>
                2 - Fair
            </option>

            <option value="3" <?php if ($feedback["clarity_rating"] == 3) echo "selected"; ?>>
                3 - Good
            </option>

            <option value="4" <?php if ($feedback["clarity_rating"] == 4) echo "selected"; ?>>
                4 - Very Good
            </option>

            <option value="5" <?php if ($feedback["clarity_rating"] == 5) echo "selected"; ?>>
                5 - Excellent
            </option>

        </select>

        <br><br>


        <label>Faculty Interaction:</label>
        <br>

        <select name="interaction_rating" required>

            <option value="1" <?php if ($feedback["interaction_rating"] == 1) echo "selected"; ?>>
                1 - Poor
            </option>

            <option value="2" <?php if ($feedback["interaction_rating"] == 2) echo "selected"; ?>>
                2 - Fair
            </option>

            <option value="3" <?php if ($feedback["interaction_rating"] == 3) echo "selected"; ?>>
                3 - Good
            </option>

            <option value="4" <?php if ($feedback["interaction_rating"] == 4) echo "selected"; ?>>
                4 - Very Good
            </option>

            <option value="5" <?php if ($feedback["interaction_rating"] == 5) echo "selected"; ?>>
                5 - Excellent
            </option>

        </select>

        <br><br>


        <label>Comment:</label>
        <br>

        <textarea
            name="comment"
            rows="5"
            cols="40"
        ><?php echo htmlspecialchars($feedback["comment"]); ?></textarea>

        <br><br>

        <button type="submit">
            Update Feedback
        </button>

    </form>

    <br>

    <a href="my_feedback.php">Back to My Feedback</a>

</body>

</html>