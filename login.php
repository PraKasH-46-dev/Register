<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['loginuser'] ?? '';
    $password = $_POST['loginpassword'] ?? '';

    if (empty($username) || empty($password)) {
        die("Error: Both fields are required.");
    }

    $conn = new mysqli("localhost", "root", "", "reglogin", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


    $stmt = $conn->prepare("SELECT password FROM register WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            echo "Login successful! Welcome, $username.";
            // header("Location: welcome.php"); // Un-comment to redirect after login
            // exit();
        } else {
            echo "Error: Incorrect password.";
        }
    } else {
        echo "Error: Username not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Form not submitted.";
}
?>
