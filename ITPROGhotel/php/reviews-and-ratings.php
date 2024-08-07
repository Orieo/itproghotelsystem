<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itproghs";

try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$rooms = $connect->query("SELECT id, type, room_number FROM rooms")->fetchAll(PDO::FETCH_ASSOC);
$amenities = $connect->query("SELECT id, name FROM amenities")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review & Rating System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <style>
        .rating .fa-star {
            font-size: 24px;
        }
        .star-light {
            color: #e9ecef;
        }
        .review-card {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Review & Rating System</div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
                </div>
                <div class="form-group">
                    <textarea id="user_review" class="form-control" placeholder="Type Review Here"></textarea>
                </div>
                <div class="form-group">
                    <select id="room_id" class="form-control">
                        <option value="">Select Room/None</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['type']) ?> Room <?= $room['room_number'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select id="amenity_id" class="form-control">
                        <option value="">Select Amenity/None</option>
                        <?php foreach ($amenities as $amenity): ?>
                            <option value="<?= $amenity['id'] ?>"><?= htmlspecialchars($amenity['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group text-center">
                    <button type="button" class="btn btn-primary" id="save_review">Submit</button>
                </div>
                <div class="mt-5" id="review_content"></div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
    var rating_data = 0;

    $('#save_review').click(function(){
        var user_review = $('#user_review').val();
        var room_id = $('#room_id').val() || null;
        var amenity_id = $('#amenity_id').val() || null;

        if (user_review.trim() === '') {
            alert("Please fill in the review field.");
            return false;
        }

        if (rating_data === 0) {
            alert("Please select a rating.");
            return false;
        }

        if (room_id === null && amenity_id === null) {
            alert("Please select at least one of the room or amenity.");
            return false;
        }

        $.ajax({
            url: "submit-rating.php",
            method: "POST",
            dataType: "JSON",
            data: {
                rating_data: rating_data, 
                user_review: user_review,
                room_id: room_id,
                amenity_id: amenity_id
            },
            success: function(response){
                if(response.status === "success") {
                    load_rating_data();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("An error occurred while submitting your review. Please try again.");
            }
        });
    });

    $(document).on('mouseenter', '.submit_star', function(){
        var rating = $(this).data('rating');
        reset_background();
        for(var count = 1; count <= rating; count++){
            $('#submit_star_' + count).addClass('text-warning');
        }
    });

    $(document).on('mouseleave', '.submit_star', function(){
        reset_background();
        for(var count = 1; count <= rating_data; count++){
            $('#submit_star_' + count).removeClass('star-light');
            $('#submit_star_' + count).addClass('text-warning');
        }
    });

    $(document).on('click', '.submit_star', function(){
        rating_data = $(this).data('rating');
        for(var count = 1; count <= 5; count++){
            $('#submit_star_' + count).removeClass('text-warning');
            $('#submit_star_' + count).addClass('star-light');
        }
        for(var count = 1; count <= rating_data; count++){
            $('#submit_star_' + count).removeClass('star-light');
            $('#submit_star_' + count).addClass('text-warning');
        }
    });

    function reset_background(){
        for(var count = 1; count <= 5; count++){
            $('#submit_star_' + count).removeClass('text-warning');
            $('#submit_star_' + count).addClass('star-light');
        }
    }

    function load_rating_data(){
        $.ajax({
            url: "submit-rating.php",
            method: "POST",
            dataType: "JSON",
            data: {action: 'load_data'},
            success: function(data) {
                $('#review_content').html(data.reviews_html);
            }
        });
    }

    load_rating_data();
    </script>
</body>
</html>
