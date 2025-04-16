<?php
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // REGEX Patterns
    $usernamePattern = "/^[a-zA-Z0-9_]{4,20}$/";
    $emailPattern = "/^[\w\.-]+@[\w\.-]+\.\w{2,}$/";
    $contactPattern = "/^[6-9]\d{9}$/"; // Only if you want to validate contact in future

    // Validate Username
    if (!preg_match($usernamePattern, $username)) {
        echo '<script>alert("Invalid Username. Use 4-20 characters (letters, numbers, underscores)."); window.history.back();</script>';
        exit();
    }

    // Validate Email
    if (!preg_match($emailPattern, $email)) {
        echo '<script>alert("Invalid Email Format."); window.history.back();</script>';
        exit();
    }

    $conn = new mysqli('localhost', 'root', '', 'mini-project');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Check email
            if ($email === $row['semail']) {

                // Password check (reversed)
                if ($password === strrev($row['password'])) {
                    echo '<script>alert("Login Successful!");
                    window.location.href = "./home.html";</script>';
                    exit();
                } else {
                    echo '<script>alert("Incorrect Password");
                    window.location.href = "./index.html";</script>';
                }

            } else {
                echo '<script>alert("Incorrect Email");
                window.location.href = "./index.html";</script>';
            }

        } else {
            echo '<script>alert("Incorrect Username");
            window.location.href = "./index.html";</script>';
        }
    }
?>
