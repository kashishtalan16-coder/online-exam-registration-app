<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Exam Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .menu {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .menu a {
            display: inline-block;
            padding: 15px 25px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Welcome to Online Exam Portal</h1>
    <div class="menu">
        <!-- Clicking this opens index.html with your registration form -->
        <a href="index.html">📝 Register for Exam</a>

        <!-- View registered students in a new tab -->
        <a href="view.php" target="_blank">👥 View Registered Students</a>
    </div>
</body>
</html>