<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "itproghs";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error) {
        $response = array(
            "success" => false,
            "message" => "Connection failed: " . $conn->connect_error
        );
        echo json_encode($response);
        return;
    }

    $sql = "SELECT id, firstName, lastName, phoneNumber, password, admin_checker FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($id, $firstName, $lastName, $phoneNumber, $hashedPassword, $admin_checker);
            $stmt->fetch();

            if ($stmt->num_rows > 0) { 
                if (!isset($_SESSION[$email]['login_attempts'])) {
                    $_SESSION[$email]['login_attempts'] = 0;
                }

                if (!isset($_SESSION[$email]['last_failed_login'])) {
                    $_SESSION[$email]['last_failed_login'] = 0;
                }

                $timeout_duration = 600;

                if ($_SESSION[$email]['login_attempts'] < 4) {
                    if ($hashedPassword && password_verify($password, $hashedPassword)) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['email'] = $email;
                        $_SESSION['firstName'] = $firstName;
                        $_SESSION['lastName'] = $lastName;
                        $_SESSION['phoneNumber'] = $phoneNumber;

                        $response = array(
                            "success" => true,
                            "admin_checker" => $admin_checker
                        );

                        echo json_encode($response);
                    } else {
                        $_SESSION[$email]['login_attempts']++;
                        $_SESSION[$email]['last_failed_login'] = time();
                        $response = array(
                            "success" => false,
                            "message" => "Incorrect email or password."
                        );
                        echo json_encode($response);
                        return;
                    }
                } else {
                    if (time() - $_SESSION[$email]['last_failed_login'] < $timeout_duration) {
                        $response = array(
                            "success" => false,
                            "message" => "Maximum login attempts reached. Please try again after " . ($timeout_duration - (time() - $_SESSION[$email]['last_failed_login'])) . " seconds."
                        );
                        echo json_encode($response);
                        return;
                    } else {
                        $_SESSION[$email]['login_attempts'] = 1;
                        $_SESSION[$email]['last_failed_login'] = time();
                        $response = array(
                            "success" => false,
                            "message" => "Incorrect email or password."
                        );
                        echo json_encode($response);
                        return;
                    }
                }
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Incorrect email or password."
                );
                echo json_encode($response);
                return;
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Error executing query."
            );
            echo json_encode($response);
            return;
        }

    } else {
        $response = array(
            "success" => false,
            "message" => "Failed to prepare statement."
        );
        echo json_encode($response);
        return;
    }

} else {
    $response = array(
        "success" => false,
        "message" => "Invalid request."
    );
    echo json_encode($response);
    return;
}
?>
