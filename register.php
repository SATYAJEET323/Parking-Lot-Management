<?php
    $username = $_POST['username'];
    $semail = $_POST['semail'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'mini-project');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        $stmt1 = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt1->bind_param("s", $username);
        $stmt1->execute();
        $result = $stmt1->get_result();
        
        if ($result->num_rows === 1) {
            echo '<script>alert("Username already exists. Please choose a unique username.");
            window.location.href = "./index.html";</script>';
        } else {
            $pass = strrev($password);
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
