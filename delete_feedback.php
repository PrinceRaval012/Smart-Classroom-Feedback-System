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

// Delete only user's own feedback
$stmt = $conn->prepare(
    "DELETE FROM feedback
     WHERE id = ? AND student_id = ?"
);

$stmt->bind_param(
    "ii",
    $feedback_id,
    $student_id
);

$stmt->execute();

$stmt->close();

// Back to feedback page
header("Location: my_feedback.php");
exit();

?>