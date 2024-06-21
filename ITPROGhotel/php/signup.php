<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $lastName = trim($_POST['lastName']);
    $firstName = trim($_POST['firstName']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $profilePicture = $_FILES['profilePicture'];

    if (empty($lastName)) $errors[] = "Last Name is required.";
    if (empty($firstName)) $errors[] = "First Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($phoneNumber)) $errors[] = "Phone Number is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^(\+63|0)\d{10}$/', $phoneNumber)) {
        $errors[] = "Invalid phone number format.";
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{12,36}$/', $password)) {
        $errors[] = "Password must be 12-36 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($profilePicture['type'], $allowedTypes)) {
            $errors[] = "Only JPEG, PNG, and GIF files are allowed.";
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uploadFile = $uploadDir . basename($profilePicture['name']);

        if (!move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
            $errors[] = "Failed to upload profile picture.";
        }
    } else {
        $uploadFile = null; 
    }

    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "itproghs";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error) {
        $errors[] = "Connection failed: " . $conn->connect_error;
    }

    if (empty($errors)) {
        $sql = "SELECT COUNT(*) as count FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $errors[] = "Failed to execute email check statement: " . $stmt->error;
            } else {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($row['count'] > 0) {
                    $errors[] = "Email already exists.";
                }
            }
            $stmt->close();
        } else {
            $errors[] = "Failed to prepare statement for email check: " . $conn->error;
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO user (lastName, firstName, email, phoneNumber, password, profilePicture, admin_checker) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $admin_checker = 0; 
            $stmt->bind_param("ssssssi", $lastName, $firstName, $email, $phoneNumber, $hashedPassword, $uploadFile, $admin_checker);

            if ($stmt->execute()) {
                echo "Success";
                $stmt->close();
                $conn->close();
                exit();
            } else {
                $errors[] = "Failed to execute insert statement: " . $stmt->error;
            }
        } else {
            $errors[] = "Failed to prepare insert statement: " . $conn->error;
        }
    }

    $conn->close();

    if (!empty($errors)) {
        echo implode('<br>', $errors);
        error_log("Signup errors: " . implode(', ', $errors));
    } else {
        echo "An unexpected error occurred.";
        error_log("Unexpected signup error.");
    }
}
?>
