<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "school_db");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$student_id = $_SESSION['user_id'];

// Fetch student info
$stmt = $conn->prepare("SELECT * FROM children WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$stmt->close();

// Display info
echo "<h2>Welcome, ".htmlspecialchars($student['fullname'])."</h2>";
echo "<p>Email: ".htmlspecialchars($student['email'])."</p>";
echo "<p>Mobile: ".htmlspecialchars($student['mobile'])."</p>";
echo "<p>Course: ".htmlspecialchars($student['course'])."</p>";
echo "<p>Exam Mode: ".htmlspecialchars($student['examode'])."</p>";
echo "<p>City: ".htmlspecialchars($student['examcity'])."</p>";
echo "<p><a href='logout.php'>Logout</a></p>";

$conn->close();
?>