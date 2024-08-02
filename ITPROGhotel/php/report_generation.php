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
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Report Generation</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <link href="../admin_styles.css" rel="stylesheet" type="text/css">
        <style>
           .content {
                text-align: center;
            }
           .button-container {
                margin: 20px auto;
                width: 50%;
                text-align: center;
            }
            table {
                margin: 20px auto;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }
        </style>
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
                </form>
            </div>
            <?php
            if (isset($_POST['generate_report'])) {
                $date = $_POST['date'];

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
                              b.booked_at LIKE '%$date%' 
                          ORDER BY 
                              b.user_id, 
                              b.user_name, 
                              b.contact, 
                              b.booked_at";
                  $result = $conn->query($sql);
                  $total_revenue = 0;
              
                  if ($result->num_rows > 0) {
                      echo "<h2>Bookings Report for $date</h2>";
                      echo "<table border='1'>";
                      echo "<tr><th>User ID</th><th>User Name</th><th>Contact</th><th>Total Price</th><th>Booked At</th></tr>";
              
                      while($row = $result->fetch_assoc()) {
                          echo "<tr><td>". $row["user_id"]. "</td><td>". $row["user_name"]. "</td><td>". $row["contact"]. "</td><td>₱". number_format($row["total_price"], 2). "</td><td>". $row["booked_at"]. "</td></tr>";
                          $total_revenue += $row["total_price"];
                      }
              
                      echo "</table>";
                      echo "<h3>Total Revenue for $date: ₱". number_format($total_revenue, 2). "</h3>";
                  } else {
                      echo "No bookings found for $date.";
                  }
              }
              
                generateBookingsReport($conn, $date);
            }
           ?>
        </div>
    </body>
</html>

<?php
// Close connection
$conn->close();
?>