<?php
ob_start(); // Start output buffering
session_start();
require 'fpdf/fpdf.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: verify_patient.php?predictor=diabetes");
    exit();
}

// Database connection
include('config.php');

// Fetch patient information
$patient_id = $_SESSION['patient_id'];
$query = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
$result = mysqli_query($conn, $query);

// Check if the query was successful and data exists
if ($result && mysqli_num_rows($result) > 0) {
    $patient = mysqli_fetch_assoc($result);
} else {
    echo "Error: Patient data not found. Please check the patient ID.";
    exit();
}

// Collect input values from the form
$inputs = [
    'pregnancies' => $_POST['pregnancies'],
    'glucose' => $_POST['glucose'],
    'bloodpressure' => $_POST['bloodpressure'],
    'skinthickness' => $_POST['skinthickness'],
    'insulin' => $_POST['insulin'],
    'bmi' => $_POST['bmi'],
    'diabetespedigreefunction' => $_POST['diabetespedigreefunction'],
    'age' => $_POST['age'],
];

// Define normal ranges for each parameter
$normal_ranges = [
    'pregnancies' => '0-20 times',
    'glucose' => '70-180 mg/dL',
    'bloodpressure' => '80-120 mmHg',
    'skinthickness' => '10-50 mm',
    'insulin' => '15-276 μU/mL',
    'bmi' => '18.5-24.9',
    'diabetespedigreefunction' => '0.0-2.5',
    'age' => '20-70 years',
];

// Call the prediction script and get output
$output = shell_exec("python predict_diabetes.py " . implode(" ", $inputs));
$prediction = json_decode($output, true);
// Generate PDF report
$pdf = new FPDF();
$pdf->AddPage();

// Report Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Diabetes Prediction Report', 0, 1, 'C');
$pdf->Ln(10);

// Patient Information
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Patient Information', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Patient Name: ' . $patient['patient_name'], 0, 1);
$pdf->Cell(0, 10, 'Age: ' . $inputs['age'], 0, 1);
$pdf->Cell(0, 10, 'Sex: ' . $patient['sex'], 0, 1);
$pdf->Ln(10);

// Input Values and Normal Ranges
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Input Values Compared to Normal Ranges', 0, 1);
$pdf->SetFont('Arial', '', 12);

foreach ($inputs as $key => $value) {
    $label = ucfirst($key);
    $normal_range = isset($normal_ranges[$key]) ? $normal_ranges[$key] : 'N/A';
    
    // Display input values and their normal ranges
    $pdf->Cell(0, 10, "$label: $value (Normal Range: $normal_range)", 0, 1);
}
$pdf->Ln(10);

// Prediction Result
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Prediction Result', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Inference: ' . ($prediction['result'] == 1 ? "High risk of diabetes." : "Low risk of diabetes."), 0, 1);
$pdf->Ln(10);

$patient_name_sanitized = preg_replace('/[^a-zA-Z0-9_]/', '_', $patient['patient_name']);
$file_name = $patient_name_sanitized . '_Diabetes_Report.pdf';

// Output the PDF with the dynamic file name
ob_clean(); // Clear any additional output before PDF generation
$pdf->Output('D', $file_name);

exit();
?>
