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

// Add a room type
function addRoomType($type, $price, $availability) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO rooms (type, price_per_night, availability) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $type, $price, $availability);
    $stmt->execute();
    $stmt->close();
}

// Edit a room type
function editRoomType($id, $type, $price, $availability) {
    global $conn;
    $stmt = $conn->prepare("UPDATE rooms SET type = ?, price_per_night = ?, availability = ? WHERE id = ?");
    $stmt->bind_param("siii", $type, $price, $availability, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete a room type
function deleteRoomType($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Update room availability
function updateRoomAvailability($id, $availability) {
    global $conn;
    $stmt = $conn->prepare("UPDATE rooms SET availability = ? WHERE id = ?");
    $stmt->bind_param("ii", $availability, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = "";
    $error = "";
    if (isset($_POST['add'])) {
        if (array_key_exists($_POST['type'], $roomTypes)) {
            addRoomType($_POST['type'], $roomTypes[$_POST['type']], $_POST['availability']);
            $success = "Room added successfully.";
        } else {
            $error = "Invalid room type.";
        }
    } elseif (isset($_POST['edit'])) {
        if (array_key_exists($_POST['type'], $roomTypes)) {
            editRoomType($_POST['id'], $_POST['type'], $roomTypes[$_POST['type']], $_POST['availability']);
            $success = "Room edited successfully.";
        } else {
            $error = "Invalid room type.";
        }
    } elseif (isset($_POST['delete'])) {
        deleteRoomType($_POST['id']);
        $success = "Room deleted successfully.";
    } elseif (isset($_POST['update'])) {
        updateRoomAvailability($_POST['id'], $_POST['availability']);
        $success = "Room availability updated successfully.";
    }
}

// Retrieve existing rooms
function getRooms() {
    global $conn;
    $result = $conn->query("SELECT * FROM rooms");
    return $result->fetch_all(MYSQLI_ASSOC);
}

$rooms = getRooms();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Room Management</title>
    <link href="../roommgmt.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">

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
            <a href="admin_home.php">Admin Home</a>
            <a href="admin_profile.php"><i class="fas fa-user-circle"></i>Admin Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
    <div class="container">
        <h2>Room Management</h2>
        <?php if (!empty($success)): ?>
            <div class="success-messages"><?= $success ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="error-messages"><?= $error ?></div>
        <?php endif; ?>
        <div class="toggle-container">
            <button class="active" onclick="showForm('add-form')">Add Room</button>
            <button onclick="showForm('edit-form')">Edit Room</button>
            <button onclick="showForm('delete-form')">Delete Room</button>
            <button onclick="showForm('update-availability-form')">Update Availability</button>
        </div>

        <div id="add-form" class="form-container active">
            <h3>Add Room Type</h3>
            <form method="post">
                <label for="type">Room Type:</label>
                <select id="type" name="type">
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Suite">Suite</option>
                </select>
                <label for="availability">Availability (0 for Available, 1 for Unavailable):</label>
                <input type="number" id="availability" name="availability" min="0" max="1" required>
                <button type="submit" name="add">Add Room</button>
            </form>
        </div>

        <div id="edit-form" class="form-container">
            <h3>Edit Room Type</h3>
            <form method="post">
                <label for="id">Room ID:</label>
                <select id="id" name="id">
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= $room['id'] ?> (<?= $room['type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <label for="type">Room Type:</label>
                <select id="type" name="type">
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Suite">Suite</option>
                </select>
                <label for="availability">Availability (0 for Available, 1 for Unavailable):</label>
                <input type="number" id="availability" name="availability" min="0" max="1" required>
                <button type="submit" name="edit">Edit Room</button>
            </form>
        </div>

        <div id="delete-form" class="form-container">
            <h3>Delete Room Type</h3>
            <form method="post">
                <label for="id">Room ID:</label>
                <select id="id" name="id">
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= $room['id'] ?> (<?= $room['type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="delete">Delete Room</button>
            </form>
        </div>

        <div id="update-availability-form" class="form-container">
            <h3>Update Room Availability</h3>
            <form method="post">
                <label for="id">Room ID:</label>
                <select id="id" name="id">
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= $room['id'] ?> (<?= $room['type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <label for="availability">Availability (0 for Available, 1 for Unavailable):</label>
                <input type="number" id="availability" name="availability" min="0" max="1" required>
                <button type="submit" name="update">Update Availability</button>
            </form>
        </div>
        <h3>Existing Rooms</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Price per Night</th>
                <th>Availability</th>
            </tr>
            <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?php echo $room['id']; ?></td>
                <td><?php echo $room['type']; ?></td>
                <td><?php echo $room['price_per_night']; ?></td>
                <td><?php echo $room['availability']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

