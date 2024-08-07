<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$connect = new PDO("mysql:host=localhost;dbname=itproghs", "root", "");

if(isset($_POST["rating_data"])) {
    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit;
    }

    $room_id = !empty($_POST["room_id"]) ? $_POST["room_id"] : null;
    $amenity_id = !empty($_POST["amenity_id"]) ? $_POST["amenity_id"] : null;

    if ($room_id === null && $amenity_id === null) {
        echo json_encode(["status" => "error", "message" => "Please select at least one of the room or amenity."]);
        exit;
    }

    $data = array(
        ':user_id'  => $_SESSION['id'], 
        ':user_name'  => $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
        ':room_id' => $room_id, 
        ':amenity_id' => $amenity_id, 
        ':user_rating'  => $_POST["rating_data"],
        ':user_review'  => $_POST["user_review"],
        ':datetime' => date("Y-m-d H:i:s")
    );

    $query = "
    INSERT INTO reviews 
    (user_id, user_name, room_id, amenity_id, user_rating, user_review, datetime) 
    VALUES (:user_id, :user_name, :room_id, :amenity_id, :user_rating, :user_review, :datetime)
    ";

    $statement = $connect->prepare($query);

    if ($statement->execute($data)) {
        echo json_encode(["status" => "success", "message" => "Your Review & Rating Successfully Submitted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "An error occurred while submitting your review. Please try again."]);
    }
}

if(isset($_POST["action"])) {
    $query = "
    SELECT r.*, 
           CASE WHEN r.room_id IS NOT NULL THEN (SELECT CONCAT(type, ' Room ', room_number) FROM rooms WHERE id = r.room_id) ELSE NULL END AS room_name,
           CASE WHEN r.amenity_id IS NOT NULL THEN (SELECT name FROM amenities WHERE id = r.amenity_id) ELSE NULL END AS amenity_name
    FROM reviews r 
    ORDER BY r.datetime DESC
    ";

    $statement = $connect->prepare($query);

    if($statement->execute()) {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $reviews_html = '';

        foreach($result as $row) {
            $reviews_html .= '
            <div class="card review-card">
                <div class="card-body">
                    <h5 class="card-title">'.htmlspecialchars($row['user_name']).'</h5>
                    <p class="card-text">'.htmlspecialchars($row['user_review']).'</p>
                    <div class="mb-2">';

            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $row['user_rating']) {
                    $reviews_html .= '<i class="fas fa-star text-warning mr-1"></i>';
                } else {
                    $reviews_html .= '<i class="fas fa-star star-light mr-1"></i>';
                }
            }

            $reviews_html .= '</div>';

            if ($row['room_name']) {
                $reviews_html .= '<p class="mb-1"><strong>Room:</strong> ' . htmlspecialchars($row['room_name']) . '</p>';
            }
            if ($row['amenity_name']) {
                $reviews_html .= '<p class="mb-1"><strong>Amenity:</strong> ' . htmlspecialchars($row['amenity_name']) . '</p>';
            }

            $reviews_html .= '
                </div>
            </div>';
        }

        $response = array('reviews_html' => $reviews_html);
        echo json_encode($response);
    }
}
?>
