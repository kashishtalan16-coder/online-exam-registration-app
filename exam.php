<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php"); // Redirect if not logged in
    exit;
}

$conn = new mysqli("localhost","root","","school_db");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$student_id = $_SESSION['user_id'];

// If form submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $exam_name = $_POST['exam'];
    $total_questions = 5; // sample
    $score = 0;

    // Sample questions (hardcoded for now)
    $answers = ["A","C","B","D","A"]; // correct answers

    for($i=1; $i<=$total_questions; $i++){
        if(isset($_POST["q$i"]) && $_POST["q$i"] == $answers[$i-1]){
            $score++;
        }
    }

    // Save to database
    $stmt = $conn->prepare("INSERT INTO exam_results(student_id, exam_name, score, total_questions) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $student_id, $exam_name, $score, $total_questions);
    $stmt->execute();
    echo "<p style='color:green;'>Exam submitted! Your score: $score / $total_questions</p>";
}
?>

<h2>Take Exam</h2>
<form method="POST" action="">
    <label>Select Exam:</label>
    <select name="exam" required>
        <option value="JEE Main">JEE Main</option>
        <option value="NEET">NEET</option>
        <option value="Other Exams">Other Exams</option>
    </select>

    <h3>Sample Questions</h3>
    <ol>
        <li>Question 1: Answer? 
            <input type="text" name="q1">
        </li>
        <li>Question 2: Answer? 
            <input type="text" name="q2">
        </li>
        <li>Question 3: Answer? 
            <input type="text" name="q3">
        </li>
        <li>Question 4: Answer? 
            <input type="text" name="q4">
        </li>
        <li>Question 5: Answer? 
            <input type="text" name="q5">
        </li>
    </ol>
    <button type="submit">Submit Exam</button>
</form>