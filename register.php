<?php
// ------------------------------
// Show all errors for debugging
// ------------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ------------------------------
// Database connection
// ------------------------------
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "school_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ------------------------------
// Handle form submission
// ------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect POST data safely
    $fullname    = trim($_POST['fullname'] ?? '');
    $fathername  = trim($_POST['fathername'] ?? '');
    $mothername  = trim($_POST['mothername'] ?? '');
    $dob         = $_POST['dob'] ?? '';
    $gender      = $_POST['gender'] ?? '';
    $email       = trim($_POST['email'] ?? '');
    $mobile      = trim($_POST['mobile'] ?? '');
    $course      = trim($_POST['course'] ?? '');
    $category    = $_POST['category'] ?? '';
    $exam        = $_POST['exam'] ?? '';
    $exam_mode   = $_POST['exam_mode'] ?? '';
    $exam_city   = trim($_POST['exam_city'] ?? '');
    $exam_date   = $_POST['exam_date'] ?? '';
    $exam_time   = $_POST['exam_time'] ?? '';
    $password    = $_POST['password'] ?? '';

    $errors = [];

    // ------------------------------
    // Validation
    // ------------------------------
    if (empty($fullname)) $errors[] = "Full Name is required.";
    if (empty($fathername)) $errors[] = "Father's Name is required.";
    if (empty($mothername)) $errors[] = "Mother's Name is required.";
    if (empty($dob)) $errors[] = "Date of Birth is required.";
    if (empty($gender)) $errors[] = "Gender is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid Email is required.";
    if (empty($mobile) || !preg_match("/^\d{10}$/", $mobile)) $errors[] = "Valid 10-digit Mobile number required.";
    if (empty($course)) $errors[] = "Course is required.";
    if (empty($category)) $errors[] = "Category is required.";
    if (empty($exam)) $errors[] = "Exam selection is required.";
    if (empty($exam_mode)) $errors[] = "Exam mode is required.";
    if (empty($exam_city)) $errors[] = "Exam City is required.";
    if (empty($exam_date)) $errors[] = "Exam Date is required.";
    if (empty($exam_time)) $errors[] = "Exam Time is required.";
    if (empty($password) || strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

    // ------------------------------
    // Handle file uploads
    // ------------------------------
    $photo_name = $signature_name = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        if (!is_dir('photos')) mkdir('photos', 0777, true);
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid('photo_') . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "photos/" . $photo_name);
    } else $errors[] = "Photo upload failed.";

    if (isset($_FILES['signature']) && $_FILES['signature']['error'] === 0) {
        if (!is_dir('signatures')) mkdir('signatures', 0777, true);
        $ext = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
        $signature_name = uniqid('sign_') . "." . $ext;
        move_uploaded_file($_FILES['signature']['tmp_name'], "signatures/" . $signature_name);
    } else $errors[] = "Signature upload failed.";

    // ------------------------------
    // Check duplicate email
    // ------------------------------
    $stmt = $conn->prepare("SELECT id FROM children WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Email already registered.";
    $stmt->close();

    // ------------------------------
    // Insert into database if no errors
    // ------------------------------
    if (count($errors) === 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO children 
            (fullname,fathername,mothername,dob,gender,email,mobile,course,category,exam,exam_mode,exam_city,exam_date,exam_time,photo,signature,password)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssssssssssss",
            $fullname,$fathername,$mothername,$dob,$gender,
            $email,$mobile,$course,$category,$exam,
            $exam_mode,$exam_city,$exam_date,$exam_time,$photo_name,$signature_name,$hashed_password
        );
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Registration Successful! <a href='index.php'>Back to Home</a></p>";
        } else {
            echo "<p style='color:red;'>Database Error: ".$stmt->error."</p>";
        }
        $stmt->close();
    } else {
        foreach ($errors as $err) echo "<p style='color:red;'>$err</p>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Online Exam Registration</title>
<style>
body { font-family: Arial, sans-serif; padding: 20px; }
h1 { color: #333; }
form { max-width: 600px; margin: auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
label { display: block; margin-top: 10px; }
input, select { width: 100%; padding: 8px; margin-top: 4px; }
button { margin-top: 15px; padding: 10px 20px; background: #007BFF; color: #fff; border: none; cursor: pointer; border-radius: 5px; }
button:hover { background: #0056b3; }
</style>
</head>
<body>
<h1>Online Exam Registration</h1>
<form action="" method="POST" enctype="multipart/form-data">
    <label>Full Name:</label>
    <input type="text" name="fullname" required>

    <label>Father's Name:</label>
    <input type="text" name="fathername" required>

    <label>Mother's Name:</label>
    <input type="text" name="mothername" required>

    <label>DOB:</label>
    <input type="date" name="dob" required>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Mobile:</label>
    <input type="text" name="mobile" required>

    <label>Course:</label>
    <input type="text" name="course" required>

    <label>Category:</label>
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="general">General</option>
        <option value="obc">OBC</option>
        <option value="sc">SC</option>
        <option value="st">ST</option>
    </select>

    <label>Exam:</label>
    <select name="exam" required>
        <option value="">Select Exam</option>
        <option value="jee-main">JEE Main</option>
        <option value="neet">NEET</option>
        <option value="others">Other Exams</option>
    </select>

    <label>Exam Mode:</label>
    <select name="exam_mode" required>
        <option value="">Select Mode</option>
        <option value="online">Online</option>
        <option value="offline">Offline</option>
    </select>

    <label>Exam City:</label>
    <input type="text" name="exam_city" required>

    <label>Exam Date:</label>
    <input type="date" name="exam_date" required>

    <label>Exam Time:</label>
    <input type="time" name="exam_time" required>

    <label>Photo:</label>
    <input type="file" name="photo" accept="image/*" required>

    <label>Signature:</label>
    <input type="file" name="signature" accept="image/*" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
</form>
</body>
</html>