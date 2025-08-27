<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username     = $_POST['username'] ?? '';
    $email        = $_POST['email'] ?? '';
    $mobileno     = $_POST['mobileno'] ?? '';
    $department   = $_POST['department'] ?? '';
    $classsection = $_POST['classsection'] ?? '';
    $upswd1       = $_POST['upswd1'] ?? '';
    $upswd2       = $_POST['upswd2'] ?? '';

    if (!empty($username) && !empty($email) && !empty($mobileno) &&
        !empty($department) && !empty($classsection) &&
        !empty($upswd1) && !empty($upswd2)) {

        if ($upswd1 !== $upswd2) {
            die("Error: Passwords do not match.");
        }

        $hashedPassword = password_hash($upswd1, PASSWORD_DEFAULT);
       $conn = new mysqli("localhost", "root", "", "reglogin", 3307);
if ($conn->connect_error) {
    die('Database Connection Failed: ' . $conn->connect_error);
}


        $SELECT = "SELECT email FROM register WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Error: Someone already registered using this email.";
        } else {
            $stmt->close();
            $INSERT = "INSERT INTO register (username, email, mobileno, department, classsection, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ssisss", $username, $email, $mobileno, $department, $classsection, $hashedPassword);

            if ($stmt->execute()) {
                echo "New record inserted successfully!";
            } else {
                echo "Error: Could not insert data. " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: All fields are required.";
    }
} else {
    echo "Form not submitted yet.";
}
?>
