<?php
session_start();
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","school_db");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT * FROM children WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Safely assign values to avoid undefined array key warnings
$fullname      = isset($row['fullname']) ? $row['fullname'] : 'Not Set';
$email         = isset($row['email']) ? $row['email'] : 'Not Set';
$mobile        = isset($row['mobile']) ? $row['mobile'] : 'Not Set';
$course        = isset($row['course']) ? $row['course'] : 'Not Set';
$exam_mode     = isset($row['exam_mode']) ? $row['exam_mode'] : 'Not Set';
$exam_date     = isset($row['exam_date']) ? $row['exam_date'] : 'Not Set';
$exam_time     = isset($row['exam_time']) ? $row['exam_time'] : 'Not Set';
$exam_city     = isset($row['exam_city']) ? $row['exam_city'] : 'Not Set';
$photo         = isset($row['photo']) ? $row['photo'] : '';
$signature     = isset($row['signature']) ? $row['signature'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f9f9f9; }
        h1 { color: #333; }
        h3 { color: #555; margin-top: 20px; }
        p { font-size: 14px; margin: 5px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: bold; }
        .btn:hover { background: #0056b3; }
        .logout { margin-top: 25px; display: block; }
        .details { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; max-width: 800px; display: flex; gap: 20px; }
        .info { flex: 1; }
        .media { flex: 0 0 150px; display: flex; flex-direction: column; gap: 10px; }
        .media img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($fullname); ?></h1>

    <div class="details">
        <div class="info">
            <h3>Your Details:</h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($mobile); ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($course); ?></p>
            <p><strong>Exam Mode:</strong> <?= htmlspecialchars($exam_mode); ?></p>
            <p><strong>Exam Date:</strong> <?= htmlspecialchars($exam_date); ?></p>
            <p><strong>Exam Time:</strong> <?= htmlspecialchars($exam_time); ?></p>
            <p><strong>Exam Centre:</strong> <?= htmlspecialchars($exam_city); ?></p>

            <!-- Download Admit Card Button -->
            <a class="btn" href="admitcard.php" target="_blank">Download Admit Card</a>

            <!-- Logout Link -->
            <a class="logout" href="logout.php">Logout</a>
        </div>

        <div class="media">
            <!-- Student Photo -->
            <?php if(!empty($photo) && file_exists("photos/".$photo)): ?>
                <img src="photos/<?= htmlspecialchars($photo); ?>" alt="Student Photo">
            <?php else: ?>
                <img src="photos/default.png" alt="No Photo">
            <?php endif; ?>

            <!-- Signature -->
            <?php if(!empty($signature) && file_exists("signatures/".$signature)): ?>
                <img src="signatures/<?= htmlspecialchars($signature); ?>" alt="Signature">
            <?php else: ?>
                <img src="signatures/default.png" alt="No Signature">
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>