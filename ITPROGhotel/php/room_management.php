<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'itproghs');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define room types and prices
$roomTypes = [
    'Single' => 799,
    'Double' => 999,
    'Suite' => 1499
];

// Function to add a room type
function addRoomType($type, $price, $availability) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO rooms (type, price_per_night, availability) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $type, $price, $availability);
    $stmt->execute();
    $stmt->close();
}

// Function to edit a room type
function editRoomType($id, $type, $price, $availability) {
    global $conn;
    $stmt = $conn->prepare("UPDATE rooms SET type = ?, price_per_night = ?, availability = ? WHERE id = ?");
    $stmt->bind_param("sdii", $type, $price, $availability, $id);
    $stmt->execute();
    $stmt->close();
}

// Function to delete a room type
function deleteRoomType($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Function to update room availability
function updateRoomAvailability($id, $availability) {
    global $conn;
    $stmt = $conn->prepare("UPDATE rooms SET availability = ? WHERE id = ?");
    $stmt->bind_param("ii", $availability, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        if (array_key_exists($_POST['type'], $roomTypes)) {
            addRoomType($_POST['type'], $roomTypes[$_POST['type']], $_POST['availability']);
        } else {
            $error = "Invalid room type.";
        }
    } elseif (isset($_POST['edit'])) {
        if (array_key_exists($_POST['type'], $roomTypes)) {
            editRoomType($_POST['id'], $_POST['type'], $roomTypes[$_POST['type']], $_POST['availability']);
        } else {
            $error = "Invalid room type.";
        }
    } elseif (isset($_POST['delete'])) {
        deleteRoomType($_POST['id']);
    } elseif (isset($_POST['update'])) {
        updateRoomAvailability($_POST['id'], $_POST['availability']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Room Management</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .navtop {
            background-color: #2f3947;
            height: 60px;
            width: 100%;
            border: 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navtop div {
            display: flex;
            margin: 0 auto;
            width: 1000px;
            height: 100%;
            align-items: center;
        }

        .navtop div h1, .navtop div a {
            display: inline-flex;
            align-items: center;
        }

        .navtop div h1 {
            flex: 1;
            font-size: 24px;
            padding: 0;
            margin: 0;
            color: #eaebed;
            font-weight: normal;
        }

        .navtop div a {
            padding: 0 20px;
            text-decoration: none;
            color: #c1c4c8;
            font-weight: bold;
        }

        .navtop div a i {
            padding: 2px 8px 0 0;
        }

        .navtop div a:hover {
            color: #eaebed;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px; /* Adjusted for fixed navbar */
        }

        .form-container {
            width: 100%;
            display: none;
        }

        .form-container.active {
            display: block;
        }

        .toggle-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .toggle-container button {
            background-color: #007BFF;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 25px;
            color: white;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .toggle-container button.active {
            background-color: #0056b3;
        }

        .toggle-container button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        input[type=text], input[type=number] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button[type=submit] {
            background-color: #007BFF;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button[type=submit]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .error-messages {
            color: red;
            margin-top: 10px;
            font-size: 14px;
            text-align: left;
            width: 100%;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
                width: 100%;
                max-width: 100%;
            }

            input[type=text], input[type=number], button[type=submit] {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
    <script>
        function showForm(formId) {
            const forms = document.querySelectorAll('.form-container');
            forms.forEach(form => form.classList.remove('active'));
            document.getElementById(formId).classList.add('active');

            const buttons = document.querySelectorAll('.toggle-container button');
            buttons.forEach(button => button.classList.remove('active'));
            document.querySelector(`[onclick="showForm('${formId}')"]`).classList.add('active');
        }
    </script>
</head>
<body>
    <nav class="navtop">
        <div>
            <h1>MotelEase</h1>
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="room_management.php"><i class="fas fa-cogs"></i>Room Management</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
    <div class="container">
        <h2>Room Management</h2>
        <div class="toggle-container">
            <button onclick="showForm('addForm')">Add Room</button>
            <button onclick="showForm('editForm')">Edit Room</button>
            <button onclick="showForm('deleteForm')">Delete Room</button>
            <button onclick="showForm('updateForm')">Update Availability</button>
        </div>

        <!-- Add Room Form -->
        <div id="addForm" class="form-container active">
            <form action="room_management.php" method="post">
                <h3>Add Room Type</h3>
                <label for="type">Room Type:</label>
                <select name="type" id="type" required>
                    <?php foreach ($roomTypes as $type => $price): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?> (<?php echo $price; ?> PHP)</option>
                    <?php endforeach; ?>
                </select>
                <label for="availability">Availability:</label>
                <input type="number" name="availability" id="availability" min="0" required>
                <button type="submit" name="add">Add Room</button>
                <?php if (isset($error)): ?>
                    <div class="error-messages"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Edit Room Form -->
        <div id="editForm" class="form-container">
            <form action="room_management.php" method="post">
                <h3>Edit Room Type</h3>
                <label for="id">Room ID:</label>
                <input type="number" name="id" id="id" min="1" required>
                <label for="type">Room Type:</label>
                <select name="type" id="type" required>
                    <?php foreach ($roomTypes as $type => $price): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?> (<?php echo $price; ?> PHP)</option>
                    <?php endforeach; ?>
                </select>
                <label for="availability">Availability:</label>
                <input type="number" name="availability" id="availability" min="0" required>
                <button type="submit" name="edit">Edit Room</button>
                <?php if (isset($error)): ?>
                    <div class="error-messages"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Delete Room Form -->
        <div id="deleteForm" class="form-container">
            <form action="room_management.php" method="post">
                <h3>Delete Room Type</h3>
                <label for="id">Room ID:</label>
                <input type="number" name="id" id="id" min="1" required>
                <button type="submit" name="delete">Delete Room</button>
                <?php if (isset($error)): ?>
                    <div class="error-messages"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Update Room Availability Form -->
        <div id="updateForm" class="form-container">
            <form action="room_management.php" method="post">
                <h3>Update Room Availability</h3>
                <label for="id">Room ID:</label>
                <input type="number" name="id" id="id" min="1" required>
                <label for="availability">Availability:</label>
                <input type="number" name="availability" id="availability" min="0" required>
                <button type="submit" name="update">Update Availability</button>
                <?php if (isset($error)): ?>
                    <div class="error-messages"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
