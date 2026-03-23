<?php
session_start();
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

require_once('tcpdf/tcpdf.php'); // Include TCPDF library

$conn = new mysqli("localhost","root","","school_db");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT * FROM children WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Safely assign values with defaults
$fullname    = isset($row['fullname']) ? $row['fullname'] : 'Not Set';
$fathername  = isset($row['fathername']) ? $row['fathername'] : 'Not Set';
$course      = isset($row['course']) ? $row['course'] : 'Not Set';
$exam_mode   = isset($row['exam_mode']) ? $row['exam_mode'] : 'Not Set';
$exam_date   = isset($row['exam_date']) ? $row['exam_date'] : 'Not Set';
$exam_time   = isset($row['exam_time']) ? $row['exam_time'] : 'Not Set';
$exam_city   = isset($row['exam_city']) ? $row['exam_city'] : 'Not Set';
$photo       = (!empty($row['photo']) && file_exists('photos/'.$row['photo'])) ? 'photos/'.$row['photo'] : 'photos/default.png';
$signature   = (!empty($row['signature']) && file_exists('signatures/'.$row['signature'])) ? 'signatures/'.$row['signature'] : 'signatures/default.png';

// Create PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Online Exam System');
$pdf->SetTitle('Admit Card');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// Heading
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'ADMIT CARD', 0, 1, 'C');
$pdf->Ln(5);

// Student Photo
$pdf->Image($photo, 150, 30, 40, 40, '', '', '', false, 300);

// Student Info
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(10);
$pdf->Cell(40, 7, 'Full Name:', 0, 0);
$pdf->Cell(0, 7, $fullname, 0, 1);
$pdf->Cell(40, 7, 'Father Name:', 0, 0);
$pdf->Cell(0, 7, $fathername, 0, 1);
$pdf->Cell(40, 7, 'Course:', 0, 0);
$pdf->Cell(0, 7, $course, 0, 1);
$pdf->Cell(40, 7, 'Exam Mode:', 0, 0);
$pdf->Cell(0, 7, $exam_mode, 0, 1);
$pdf->Cell(40, 7, 'Exam Date:', 0, 0);
$pdf->Cell(0, 7, $exam_date, 0, 1);
$pdf->Cell(40, 7, 'Exam Time:', 0, 0);
$pdf->Cell(0, 7, $exam_time, 0, 1);
$pdf->Cell(40, 7, 'Exam Centre:', 0, 0);
$pdf->Cell(0, 7, $exam_city, 0, 1);

// Signature
$pdf->Ln(10);
$pdf->Cell(0, 7, 'Signature:', 0, 1);
$pdf->Image($signature, 15, $pdf->GetY(), 50, 20, '', '', '', false, 300);

// Instructions
$pdf->Ln(30);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 7, 'Important Instructions:', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$instructions = "
1. Carry this admit card along with a valid photo ID.
2. Reach the exam centre 30 minutes before the exam.
3. Mobile phones and electronic devices are not allowed.
4. Follow all instructions given by the invigilator.
5. Any malpractice will lead to disqualification.
";
$pdf->MultiCell(0, 6, $instructions, 0, 'L');

// Output PDF
$pdf->Output('AdmitCard_'.$fullname.'.pdf', 'I');

$conn->close();
?>