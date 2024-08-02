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

// Make user an admin
function makeAdmin($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE user SET admin_checker = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Get all admins
function getAdmins() {
    global $conn;
    $result = $conn->query("SELECT * FROM user WHERE admin_checker = 1");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add an admin
function addAdmin($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE user SET admin_checker = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Remove an admin
function removeAdmin($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE user SET admin_checker = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions for admin status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_admin'])) {
        addAdmin($_POST['id']);
        $success = "Admin added successfully.";
    } elseif (isset($_POST['remove_admin'])) {
        removeAdmin($_POST['id']);
        $success = "Admin removed successfully.";
    }
}

$admins = getAdmins();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = "";
    $error = "";
    if (isset($_POST['delete'])) {
        deleteUser($_POST['id']);
        $success = "User deleted successfully.";
    } elseif (isset($_POST['make_admin'])) {
        makeAdmin($_POST['id']);
        $success = "User promoted to admin successfully.";
    } elseif (isset($_POST['search'])) {
        $users = searchUser($_POST['email']);
    }
}

if (!isset($users)) {
    $users = getUsers();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Management</title>
    <link href="../usermgmt.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>

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
            <table>
                <tr>
                    <th style="text-align: left; padding-right: 20px;">ID</th>
                    <th style="text-align: left; padding-right: 20px;">First Name</th>
                    <th style="text-align: left; padding-right: 20px;">Last Name</th>
                    <th style="text-align: left; padding-right: 20px;">Email</th>
                    <th style="text-align: left; padding-right: 20px;">Phone Number</th>
                    <th style="text-align: left; padding-right: 20px;">Admin Status</th>
                </tr>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $user['id']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $user['firstName']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $user['lastName']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $user['email']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $user['phoneNumber']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $user['admin_checker'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="search-form" class="form-container">
            <h3>Search User</h3>
            <form action="user_management.php" method="post">
                <input type="hidden" name="search">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Search</button>
            </form>
            <?php if (isset($users) && count($users) > 0): ?>
                <h4>Search Results</h4>
                <table>
                    <tr>
                        <th style="text-align: left; padding-right: 20px;">ID</th>
                        <th style="text-align: left; padding-right: 20px;">First Name</th>
                        <th style="text-align: left; padding-right: 20px;">Last Name</th>
                        <th style="text-align: left; padding-right: 20px;">Email</th>
                        <th style="text-align: left; padding-right: 20px;">Phone Number</th>
                        <th style="text-align: left; padding-right: 20px;">Admin Status</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td style="text-align: left; padding-right: 20px;"><?php echo $user['id']; ?></td>
                        <td style="text-align: left; padding-right: 20px;"><?php echo $user['firstName']; ?></td>
                        <td style="text-align: left; padding-right: 20px;"><?php echo $user['lastName']; ?></td>
                        <td style="text-align: left; padding-right: 20px;"><?php echo $user['email']; ?></td>
                        <td style="text-align: left; padding-right: 20px;"><?php echo $user['phoneNumber']; ?></td>
                        <td style="text-align: left; padding-right: 20px;"><?php echo $user['admin_checker'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif (isset($users)): ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>

        <div id="delete-form" class="form-container">
            <h3>Delete User</h3>
            <form action="user_management.php" method="post">
                <input type="hidden" name="delete">
                <label for="id">User ID:</label>
                <input type="text" id="id" name="id" required>
                <button type="submit">Delete</button>
            </form>
        </div>

        <div id="admin-status-form" class="form-container">
    <h3>Admin List</h3>
    
    <!-- Admin List -->
    <table>
        <tr>
            <th style="text-align: left; padding-right: 20px;">ID</th>
            <th style="text-align: left; padding-right: 20px;">First Name</th>
            <th style="text-align: left; padding-right: 20px;">Last Name</th>
            <th style="text-align: left; padding-right: 20px;">Email</th>
            <th style="text-align: left; padding-right: 20px;">Phone Number</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
        <tr>
            <td style="text-align: left; padding-right: 20px;"><?php echo $user['id']; ?></td>
            <td style="text-align: left; padding-right: 20px;"><?php echo $user['firstName']; ?></td>
            <td style="text-align: left; padding-right: 20px;"><?php echo $user['lastName']; ?></td>
            <td style="text-align: left; padding-right: 20px;"><?php echo $user['email']; ?></td>
            <td style="text-align: left; padding-right: 20px;"><?php echo $user['phoneNumber']; ?></td>
         </tr>
        <?php endforeach; ?>
    </table>

    
    <h4>Add Admin</h4>
    <form action="user_management.php" method="post">
        <input type="hidden" name="add_admin">
        <label for="id">User ID:</label>
        <input type="text" id="id" name="id" required>
        <button type="submit">Add Admin</button>
    </form>

    <h4>Remove Admin</h4>
    <form action="user_management.php" method="post">
        <input type="hidden" name="remove_admin">
        <label for="id">Admin ID:</label>
        <input type="text" id="id" name="id" required>
        <button type="submit">Remove Admin</button>
    </form>
</div>
    </div>
</body>
</html>
