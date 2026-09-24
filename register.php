<?php

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Check empty fields
    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $message = "All fields are required.";

    }

    // Validate name
elseif (strlen($name) < 6) {

    $message = "Name must be at least 2 characters.";

}

// Validate email
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $message = "Please enter a valid email.";

}

    // Check password match
    elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";

    }

    // Check password length
    elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    }

    else {

        // Check email already exists
        $stmt = $conn->prepare(
            "SELECT id
             FROM users
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $message = "Email already registered.";

        } else {

            // Password hashing
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            // Insert student
            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, role)
                VALUES (?, ?, ?, 'student')"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashed_password
            );


            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";

            } else {

                $message = "Registration failed.";

            }
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

    <title>Student Registration</title>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Smart Classroom Feedback System</h1>

        <h2>Student Registration</h2>


        <?php if (!empty($message)) { ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>


        <form method="POST">

            <label>Name:</label>

            <input
                type="text"
                name="name"
                placeholder="Enter your full name"
                required
            >

            <br><br>


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
                placeholder="Minimum 6 characters"
                minlength="6"
                required

            >

            <br><br>


            <label>Confirm Password:</label>

           <input
    type="password"
    name="confirm_password"
    placeholder="Confirm your password"
    minlength="6"
    required
>

            <br><br>


            <button type="submit">
                Register
            </button>

        </form>


        <p>

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </p>

    </div>

</div>

</body>

</html>