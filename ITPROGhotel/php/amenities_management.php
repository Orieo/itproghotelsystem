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

// Get all amenities
function getAmenities() {
    global $conn;
    $result = $conn->query("SELECT * FROM amenities");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add an amenity
function addAmenity($name, $price, $description, $image) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO amenities (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $description, $image);
    $stmt->execute();
    $stmt->close();
}

// Update an amenity
function updateAmenity($id, $name, $price, $description, $image = null) {
    global $conn;
    if ($image) {
        $stmt = $conn->prepare("UPDATE amenities SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $name, $price, $description, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE amenities SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $name, $price, $description, $id);
    }
    $stmt->execute();
    $stmt->close();
}

// Delete an amenity
function deleteAmenity($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM amenities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = "";
    $error = "";
    
    if (isset($_POST['add_amenity'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileType = exif_imagetype($_FILES['image']['tmp_name']);
            if (!in_array($fileType, [IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                $error = "Only JPEG and PNG files are allowed.";
            } else {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $error = "Failed to upload amenity image.";
                } else {
                    $image = $uploadFile;
                }
            }
        }

        if (empty($error)) {
            addAmenity($name, $price, $description, $image);
            $success = "Amenity added successfully.";
        }
    } elseif (isset($_POST['update_amenity'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileType = exif_imagetype($_FILES['image']['tmp_name']);
            if (!in_array($fileType, [IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                $error = "Only JPEG and PNG files are allowed.";
            } else {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $error = "Failed to upload amenity image.";
                } else {
                    $image = $uploadFile;
                }
            }
        }

        if (empty($error)) {
            updateAmenity($id, $name, $price, $description, $image);
            $success = "Amenity updated successfully.";
        }
    } elseif (isset($_POST['delete_amenity'])) {
        deleteAmenity($_POST['id']);
        $success = "Amenity deleted successfully.";
    }
}

$amenities = getAmenities();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Amenities Management</title>
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
        <h2>Amenities Management</h2>
        <?php if (!empty($success)): ?>
            <div class="success-messages"><?= $success ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="error-messages"><?= $error ?></div>
        <?php endif; ?>
        <div class="toggle-container">
            <button class="active" onclick="showForm('all-amenities-form')">All Amenities</button>
            <button onclick="showForm('add-amenity-form')">Add Amenity</button>
            <button onclick="showForm('edit-amenity-form')">Edit Amenity</button>
            <button onclick="showForm('delete-amenity-form')">Delete Amenity</button>
        </div>

        <div id="all-amenities-form" class="form-container active">
            <h3>All Amenities</h3>
            <table>
                <tr>
                    <th style="text-align: left; padding-right: 20px;">ID</th>
                    <th style="text-align: left; padding-right: 20px;">Name</th>
                    <th style="text-align: left; padding-right: 20px;">Price</th>
                    <th style="text-align: left; padding-right: 20px;">Description</th>
                    <th style="text-align: left; padding-right: 20px;">Image</th>
                </tr>
                <?php foreach ($amenities as $amenity): ?>
                <tr>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $amenity['id']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $amenity['name']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $amenity['price']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $amenity['description']; ?></td>
                    <td style="text-align: left; padding-right: 20px;"><?php echo $amenity['image'] ? '<img src="' . $amenity['image'] . '" alt="Amenity Image" style="width: 100px;">' : 'No Image'; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="add-amenity-form" class="form-container">
            <h3>Add Amenity</h3>
            <form action="amenities_management.php" method="post" enctype="multipart/form-data">
                <input type type="hidden" name="add_amenity" value="1">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" required>
                <label for="description">Description:</label>
                <textarea name="description" required></textarea>
                <label for="image">Image:</label>
                <input type="file" name="image" accept="image/jpeg, image/png">
                <input type="submit" value="Add Amenity">
            </form>
        </div>

        <div id="edit-amenity-form" class="form-container">
            <h3>Edit Amenity</h3>
            <form action="amenities_management.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="update_amenity" value="1">
                <label for="id">Amenity ID:</label>
                <input type="number" name="id" required>
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" required>
                <label for="description">Description:</label>
                <textarea name="description" required></textarea>
                <label for="image">Image (optional):</label>
                <input type="file" name="image" accept="image/jpeg, image/png">
                <input type="submit" value="Update Amenity">
            </form>
        </div>

        <div id="delete-amenity-form" class="form-container">
            <h3>Delete Amenity</h3>
            <form action="amenities_management.php" method="post">
                <input type="hidden" name="delete_amenity" value="1">
                <label for="id">Amenity ID:</label>
                <input type="number" name="id" required>
                <input type="submit" value="Delete Amenity">
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
