<?php

session_start();

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Email and password are required.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                session_regenerate_id(true);

if ($user["role"] == "admin") {
    header("Location: admin_dashboard.php");
} else {
    header("Location: dashboard.php");
}

exit();

            } else {

                $message = "Invalid email or password.";

            }

        } else {

            $message = "Invalid email or password.";

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

    <title>Login</title>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Smart Classroom Feedback System</h1>

        <h2>Login</h2>

        <?php if (!empty($message)) { ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>


        <form method="POST">

            <label>Email:</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <br><br>


            <label>Password:</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <br><br>

            <button type="submit">
                Login
            </button>

        </form>


        <p>

            Don't have an account?

            <a href="register.php">
                Register
            </a>

        </p>

    </div>

</div>

</body>

</html>