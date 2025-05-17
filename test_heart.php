<?php
ob_start(); // Start output buffering
session_start();
require 'fpdf/fpdf.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: verify_patient.php?predictor=heart");
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
    'age' => $_POST['age'],
    'sex' => $_POST['sex'],
    'cp' => $_POST['cp'],
    'trestbps' => $_POST['trestbps'],
    'chol' => $_POST['chol'],
    'fbs' => $_POST['fbs'],
    'restecg' => $_POST['restecg'],
    'thalach' => $_POST['thalach'],
    'exang' => $_POST['exang'],
    'oldpeak' => $_POST['oldpeak'],
    'slope' => $_POST['slope'],
    'ca' => $_POST['ca'],
    'thal' => $_POST['thal'],
];

// Define normal ranges for each parameter
$normal_ranges = [
    'age' => '20-70 years',
    'sex' => '1 (male) or 0 (female)',
    'cp' => '0-3 (0: Typical Angina, 1: Atypical Angina, 2: Non-anginal Pain, 3: Asymptomatic)',
    'trestbps' => '90-140 mmHg',
    'chol' => '125-200 mg/dL',
    'fbs' => '0-1 (0: <= 120 mg/dL, 1: > 120 mg/dL)',
    'restecg' => '0-2 (0: Normal, 1: ST-T Wave Abnormality, 2: Left Ventricular Hypertrophy)',
    'thalach' => '60-100 bpm',
    'exang' => '0-1 (0: No, 1: Yes)',
    'oldpeak' => '0.0-2.5 (ST depression)',
    'slope' => '0-2 (0: Upsloping, 1: Flat, 2: Downsloping)',
    'ca' => '0-3 (Number of major vessels)',
    'thal' => '1-3 (1: Normal, 2: Fixed Defect, 3: Reversible Defect)',
];

$output = shell_exec("python predict_heart.py " . implode(" ", $inputs));

$prediction = json_decode($output, true);

// Generate PDF report
$pdf = new FPDF();
$pdf->AddPage();

// Report Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Heart Disease Prediction Report', 0, 1, 'C');
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
$pdf->Cell(0, 10, 'Inference: ' . ($prediction['result'] == 1 ? "High risk of heart disease." : "Low risk of heart disease."), 0, 1);
$pdf->Ln(10);

$patient_name_sanitized = preg_replace('/[^a-zA-Z0-9_]/', '_', $patient['patient_name']);
$file_name = $patient_name_sanitized . '_Heart_Disease_Report.pdf';

// Output the PDF with the dynamic file name
ob_clean(); // Clear any additional output before PDF generation
$pdf->Output('D', $file_name);  
exit();
?>
