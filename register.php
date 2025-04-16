<?php
    $username = trim($_POST['username']);
    $semail = trim($_POST['semail']);
    $password = $_POST['password'];

    // Enhanced Validation Regex Patterns
    $usernamePattern = "/^(?!_)(?!.*__)[a-zA-Z0-9_]{4,20}(?<!_)$/";
    $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    // Input Validations
    if (!preg_match($usernamePattern, $username)) {
        echo '<script>alert("Invalid Username. Use 4-20 characters: letters, numbers, underscores. Cannot start/end with underscore or have multiple underscores."); window.history.back();</script>';
        exit();
    }

    if (!preg_match($emailPattern, $semail)) {
        echo '<script>alert("Invalid Email Format."); window.history.back();</script>';
        exit();
    }

    if (strlen($password) < 6) {
        echo '<script>alert("Password must be at least 6 characters long."); window.history.back();</script>';
        exit();
    }

    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'mini-project');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Check if username already exists
        $stmt1 = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt1->bind_param("s", $username);
        $stmt1->execute();
        $result = $stmt1->get_result();

        if ($result->num_rows === 1) {
            echo '<script>alert("Username already exists. Please choose a unique username.");
            window.location.href = "./index.html";</script>';
        } else {
            $pass = strrev($password); // Note: Replace this with password_hash() for real-world apps
            $stmt = $conn->prepare("INSERT INTO user (username, semail, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $semail, $pass);
            $stmt->execute();

            echo '<script>alert("Registration Successful!");
            window.location.href = "./index.html";</script>';

            $stmt->close();
        }

        $conn->close();
    }
?>
