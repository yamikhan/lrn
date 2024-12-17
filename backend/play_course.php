<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register.html');
    exit();
}

// Include database connection
include '../backend/db.php';

// Check if course_id is provided in the URL
if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Fetch course details from the database
    $sql = "SELECT title, description, video_path FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if course exists
    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    } else {
        $course = null;
    }

    $stmt->close();
} else {
    $course = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Course</title>
    <link rel="stylesheet" href="../css/course_play.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            color: #007BFF;
        }
        video {
            width: 100%;
            max-width: 720px;
            height: auto;
            margin: 20px 0;
            display: block;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .play-container {
            text-align: center;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="play-container">
        <?php if ($course): ?>
            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            
            <!-- Video Player -->
            <?php if (!empty($course['video_path'])): ?>
                <video controls>
                    <source src="<?php echo htmlspecialchars($course['video_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php else: ?>
                <p>No video available for this course.</p>
            <?php endif; ?>
            
            <a href="mes_etudiant_courses.php">Back to My Courses</a>
        <?php else: ?>
            <p>Sorry, the course you're looking for does not exist.</p>
        <?php endif; ?>
    </div>
</body>
</html>
