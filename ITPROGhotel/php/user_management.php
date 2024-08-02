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

// Get all users
function getUsers() {
    global $conn;
    $result = $conn->query("SELECT * FROM user");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Search a user by email
function searchUser($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Delete a user
function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Update user role
function updateUserRole($id, $role, $value) {
    global $conn;
    $stmt = $conn->prepare("UPDATE user SET $role = ? WHERE id = ?");
    $stmt->bind_param("ii", $value, $id);
    $stmt->execute();
    $stmt->close();
}

// Get all admins
function getAdmins() {
    global $conn;
    $result = $conn->query("SELECT * FROM user WHERE admin_checker = 1");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get all room managers
function getRoomManagers() {
    global $conn;
    $result = $conn->query("SELECT * FROM user WHERE room_manager = 1");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get all amenities managers
function getAmenitiesManagers() {
    global $conn;
    $result = $conn->query("SELECT * FROM user WHERE amenities_manager = 1");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Var to hold all users
$allUsers = getUsers();
$users = $allUsers; // Default to all users

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = "";
    $error = "";

    if (isset($_POST['delete'])) {
        deleteUser($_POST['delete']);
        $success = "User deleted successfully.";
    } elseif (isset($_POST['make_admin'])) {
        updateUserRole($_POST['id'], 'admin_checker', 1);
        $success = "User promoted to admin successfully.";
    } elseif (isset($_POST['remove_admin'])) {
        updateUserRole($_POST['id'], 'admin_checker', 0);
        $success = "Admin removed successfully.";
    } elseif (isset($_POST['add_roomMan'])) {
        updateUserRole($_POST['id'], 'room_manager', 1);
        $success = "Room Manager added successfully.";
    } elseif (isset($_POST['remove_roomMan'])) {
        updateUserRole($_POST['id'], 'room_manager', 0);
        $success = "Room Manager removed successfully.";
    } elseif (isset($_POST['add_amenMan'])) {
        updateUserRole($_POST['id'], 'amenities_manager', 1);
        $success = "Amenities Manager added successfully.";
    } elseif (isset($_POST['remove_amenMan'])) {
        updateUserRole($_POST['id'], 'amenities_manager', 0);
        $success = "Amenities Manager removed successfully.";
    } elseif (isset($_POST['search'])) {
        $users = searchUser($_POST['email']);
    } elseif (isset($_POST['reset_users'])) {
        $users = $allUsers; // Reset to all users
    } else {
        $users = [];
    }
}

if (!isset($users)) {
    $users = getUsers();
}

$admins = getAdmins();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Management</title>
    <link href="../usermgmt.css" rel="stylesheet" type="text/css">
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
    <style>
        .form-container {
            display: none;
        }

        .form-container.active {
            display: block;
        }

        .toggle-container button.active {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <nav class="navtop">
		<div>
			<h1>MotelEase Admin</h1>
			<a href="admin_home.php">Home</a>
			<a href="admin_profile.php"><i class="fas fa-user-circle"></i>Profile</a>
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
		</div>
	</nav>
    
    <div class="container">
        <h2>User Management</h2>
        <?php if (!empty($success)): ?>
            <div class="success-messages"><?= $success ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="error-messages"><?= $error ?></div>
        <?php endif; ?>
        <div class="toggle-container">
            <button class="active" onclick="showForm('all-users-form')">All Users</button>
            <button onclick="showForm('search-form')">Search User</button>
            <button onclick="showForm('delete-form')">Delete User</button>
            <button onclick="showForm('admin-status-form')">Admin Status</button>
        </div>

        <div id="all-users-form" class="form-container active">
            <h3>All Users</h3>
                <form action="user_management.php" method="post">
                    <input type="hidden" name="reset_users">
                    <button type="">Reset table</button>
                </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Admin Status</th>
                    <th>Room Manager</th>
                    <th>Amenities Status</th>
                </tr>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id']; ?></td>
                    <td><?= $user['firstName']; ?></td>
                    <td><?= $user['lastName']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td><?= $user['phoneNumber']; ?></td>
                    <td><?= $user['admin_checker'] ? 'Yes' : 'No'; ?></td>
                    <td><?= $user['room_manager'] ? 'Yes' : 'No'; ?></td>
                    <td><?= $user['amenities_manager'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="search-form" class="form-container">
            <h3>Search User</h3>
            <form action="" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <input type="submit" name="search" value="Search">
            </form>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search']) && !empty($users)): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Admin Status</th>
                        <th>Room Manager</th>
                        <th>Amenities Status</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= $user['firstName']; ?></td>
                        <td><?= $user['lastName']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td><?= $user['phoneNumber']; ?></td>
                        <td><?= $user['admin_checker'] ? 'Yes' : 'No'; ?></td>
                        <td><?= $user['room_manager'] ? 'Yes' : 'No'; ?></td>
                        <td><?= $user['amenities_manager'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div id="delete-form" class="form-container">
            <h3>Delete User</h3>
            <form action="" method="post">
                <label for="delete">User ID:</label>
                <input type="number" id="delete" name="delete" required>
                <input type="submit" value="Delete">
            </form>
        </div>

        <div id="admin-status-form" class="form-container">
    <h3>Admin Status</h3>
    <form action="" method="post">
        <label for="id">User ID:</label>
        <input type="number" id="id" name="id" required>
        <input type="submit" name="make_admin" value="Add Admin">
        <input type="submit" name="remove_admin" value="Remove Admin">
        <input type="submit" name="add_roomMan" value="Add Room Manager">
        <input type="submit" name="remove_roomMan" value="Remove Room Manager">
        <input type="submit" name="add_amenMan" value="Add Amenities Manager">
        <input type="submit" name="remove_amenMan" value="Remove Amenities Manager">
    </form>

    <h3>Admins</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?= $admin['id']; ?></td>
            <td><?= $admin['firstName']; ?></td>
            <td><?= $admin['lastName']; ?></td>
            <td><?= $admin['email']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Room Managers</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
        </tr>
        <?php $roomManagers = getRoomManagers(); ?>
        <?php foreach ($roomManagers as $manager): ?>
        <tr>
            <td><?= $manager['id']; ?></td>
            <td><?= $manager['firstName']; ?></td>
            <td><?= $manager['lastName']; ?></td>
            <td><?= $manager['email']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Amenities Managers</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
        </tr>
        <?php $amenitiesManagers = getAmenitiesManagers(); ?>
        <?php foreach ($amenitiesManagers as $manager): ?>
        <tr>
            <td><?= $manager['id']; ?></td>
            <td><?= $manager['firstName']; ?></td>
            <td><?= $manager['lastName']; ?></td>
            <td><?= $manager['email']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>
</body>
</html>
