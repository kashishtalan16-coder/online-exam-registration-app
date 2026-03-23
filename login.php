<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "school_db");
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Check if form submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, fullname, email, password FROM children WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        // Verify password
        if(password_verify($password, $row['password'])){
            // Set session variables
            $_SESSION['student_id'] = $row['id'];   // <-- Updated here
            $_SESSION['fullname'] = $row['fullname'];

            // Redirect to student dashboard
            header("Location: dashboard.php");       // <-- make sure this matches your dashboard file
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Online Exam</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .login-wrapper { max-width: 400px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        .submit-btn { width: 100%; padding: 10px; background: #4CAF50; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .submit-btn:hover { background: #45a049; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <h2>Login</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your registered email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
        <p style="text-align:center; margin-top:10px;">Don't have an account? <a href="index.html">Register Here</a></p>
    </div>
</body>
</html>