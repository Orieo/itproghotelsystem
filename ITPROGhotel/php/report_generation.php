<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location:../index.html');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'itproghs');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Function to generate bookings report
function generateBookingsReport($conn, $date) {
    $sql = "SELECT 
                b.user_id, 
                b.user_name, 
                b.contact, 
                b.total_price, 
                b.booked_at 
            FROM 
                bookings b 
            WHERE 
                DATE(b.booked_at) = ?
            ORDER BY 
                b.user_id, 
                b.user_name, 
                b.contact, 
                b.booked_at";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Bookings Report for $date</h2>";
        echo "<table border='1' style='margin: 0 auto;'>";
        echo "<tr><th>User ID</th><th>User Name</th><th>Contact</th><th>Total Price</th><th>Booked At</th></tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr><td>". $row["user_id"]. "</td><td>". $row["user_name"]. "</td><td>". $row["contact"]. "</td><td>₱". number_format($row["total_price"], 2). "</td><td>". $row["booked_at"]. "</td></tr>";
        }

        echo "</table>";
    } else {
        echo "No bookings found for $date.";
    }
}

// Function to generate revenue report
function generateRevenueReport($conn, $date) {
    $sql = "SELECT 
                SUM(b.total_price) AS total_revenue, 
                COUNT(b.user_id) AS total_bookings 
            FROM 
                bookings b 
            WHERE 
                DATE(b.booked_at) = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h2>Revenue Report for $date</h2>";
        echo "<table border='1' style='margin: 0 auto;'>";
        echo "<tr><th>Total Revenue</th><th>Total Bookings</th></tr>";
        echo "<tr>";
        echo "<td>₱". number_format($row["total_revenue"], 2). "</td>";
        echo "<td>". $row["total_bookings"]. "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "No revenue data found for $date.";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Report Generation</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <link href="../admin_styles.css" rel="stylesheet" type="text/css">
    </head>
    <body class="loggedin">
        <nav class="navtop">
            <div>
                <h1>MotelEase Admin</h1>
                <a href="admin_home.php">Home</a>
                <a href="admin_profile.php"><i class="fas fa-user-circle"></i>Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </nav>
        <div class="content">
            <h1>Report Generation</h1>
            <div class="button-container">
                <form method="post">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" required>
                    <input type="submit" name="generate_report" value="Generate Report">
                    <input type="submit" name="generate_xml" value="Generate XML Files">
                    </form>
                <?php
                if (isset($_POST['generate_report'])) {
                    $date = $_POST['date'];
                    generateBookingsReport($conn, $date);
                    echo "<br>";
                    generateRevenueReport($conn, $date);
                }

                if (isset($_POST['generate_xml'])) {
                    $date = $_POST['date'];
                    $bookings_xml = "<?xml version='1.0' encoding='UTF-8'?><bookings>";
                    $revenue_xml = "<?xml version='1.0' encoding='UTF-8'?><revenue>";

                    $sql = "SELECT 
                                b.user_id, 
                                b.user_name, 
                                b.contact, 
                                b.total_price, 
                                b.booked_at 
                            FROM 
                                bookings b 
                            WHERE 
                                DATE(b.booked_at) = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $date);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $bookings_xml .= "<booking>";
                            $bookings_xml .= "<user_id>". $row["user_id"]. "</user_id>";
                            $bookings_xml .= "<user_name>". $row["user_name"]. "</user_name>";
                            $bookings_xml .= "<contact>". $row["contact"]. "</contact>";
                            $bookings_xml .= "<total_price>". $row["total_price"]. "</total_price>";
                            $bookings_xml .= "<booked_at>". $row["booked_at"]. "</booked_at>";
                            $bookings_xml .= "</booking>";
                        }
                    }

                    $bookings_xml .= "</bookings>";

                    $sql = "SELECT 
                                SUM(b.total_price) AS total_revenue, 
                                COUNT(b.user_id) AS total_bookings 
                            FROM 
                                bookings b 
                            WHERE 
                                DATE(b.booked_at) = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $date);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $revenue_xml .= "<total_revenue>". $row["total_revenue"]. "</total_revenue>";
                        $revenue_xml .= "<total_bookings>". $row["total_bookings"]. "</total_bookings>";
                    }

                    $revenue_xml .= "</revenue>";

                    file_put_contents("bookings_$date.xml", $bookings_xml);
                    file_put_contents("revenue_$date.xml", $revenue_xml);

                    echo "XML files generated successfully!";
                }
                ?>
            </div>
        </div>
    </body>
</html>