<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "school_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all students
$sql = "SELECT * FROM children ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: middle; }
        th { background: #4CAF50; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .btn { padding: 5px 10px; background: #007BFF; color: #fff; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Registered Students</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Father Name</th>
                <th>Mother Name</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Course</th>
                <th>Category</th>
                <th>Exam</th>
                <th>Exam Mode</th>
                <th>Exam City</th>
                <th>Exam Date</th>
                <th>Exam Time</th>
                <th>Photo</th>
                <th>Signature</th>
                <th>Admit Card</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['fullname']); ?></td>
                <td><?= htmlspecialchars($row['fathername']); ?></td>
                <td><?= htmlspecialchars($row['mothername']); ?></td>
                <td><?= $row['dob']; ?></td>
                <td><?= htmlspecialchars($row['gender']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['mobile']); ?></td>
                <td><?= htmlspecialchars($row['course']); ?></td>
                <td><?= htmlspecialchars($row['category']); ?></td>
                <td><?= htmlspecialchars($row['exam']); ?></td>
                <td><?= htmlspecialchars($row['exam_mode']); ?></td>
                <td><?= htmlspecialchars($row['exam_city']); ?></td>
                <td><?= $row['exam_date']; ?></td>
                <td><?= $row['exam_time']; ?></td>
                <td>
                    <?php if(!empty($row['photo']) && file_exists('photos/'.$row['photo'])): ?>
                        <img src="photos/<?= $row['photo']; ?>" alt="Photo">
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(!empty($row['signature']) && file_exists('signatures/'.$row['signature'])): ?>
                        <img src="signatures/<?= $row['signature']; ?>" alt="Signature">
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn" href="admitcard.php?id=<?= $row['id']; ?>" target="_blank">Download</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No student records found.</p>
    <?php endif; ?>

<?php
$conn->close();
?>
</body>
</html>