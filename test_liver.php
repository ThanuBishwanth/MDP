<?php
ob_start(); // Start output buffering
session_start();
require 'fpdf/fpdf.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: verify_patient.php?predictor=liver");
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
    'gender' => $_POST['gender'],
    'total_bilirubin' => $_POST['total_bilirubin'],
    'direct_bilirubin' => $_POST['direct_bilirubin'],
    'alkaline_phosphotase' => $_POST['alkaline_phosphotase'],
    'alamine_aminotransferase' => $_POST['alamine_aminotransferase'],
    'aspartate_aminotransferase' => $_POST['aspartate_aminotransferase'],
    'total_protiens' => $_POST['total_protiens'],
    'albumin' => $_POST['albumin'],
    'albumin_and_globulin_ratio' => $_POST['albumin_and_globulin_ratio'],
];

// Define normal ranges for each parameter
$normal_ranges = [
    'age' => '20-70 years',
    'gender' => '1 (male) or 0 (female)',
    'total_bilirubin' => '0.1-1.2 mg/dL',
    'direct_bilirubin' => '0.0-0.3 mg/dL',
    'alkaline_phosphotase' => '44-147 IU/L',
    'alamine_aminotransferase' => '7-56 IU/L',
    'aspartate_aminotransferase' => '10-40 IU/L',
    'total_protiens' => '6.3-8.2 g/dL',
    'albumin' => '3.5-5.0 g/dL',
    'albumin_and_globulin_ratio' => '0.8-2.0',
];

// Execute Python script for liver prediction
$command = "python predict_liver.py " . implode(" ", $inputs);
$output = shell_exec($command);
$prediction = json_decode($output, true);

// Generate PDF report
$pdf = new FPDF();
$pdf->AddPage();

// Report Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Liver Disease Prediction Report', 0, 1, 'C');
$pdf->Ln(10);

// Patient Information
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Patient Information', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Patient Name: ' . $patient['patient_name'], 0, 1);
$pdf->Cell(0, 10, 'Age: ' . $patient['age'], 0, 1);
$pdf->Cell(0, 10, 'Gender: ' . $patient['sex'], 0, 1);
$pdf->Ln(10);

// Input Values and Normal Ranges
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Input Values Compared to Normal Ranges', 0, 1);
$pdf->SetFont('Arial', '', 12);

foreach ($inputs as $key => $value) {
    $label = ucfirst(str_replace("_", " ", $key));
    $normal_range = isset($normal_ranges[$key]) ? $normal_ranges[$key] : 'N/A';
    
    // Display input values and their normal ranges
    $pdf->Cell(0, 10, "$label: $value (Normal Range: $normal_range)", 0, 1);
}
$pdf->Ln(10);

// Prediction Result
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Prediction Result', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Inference: ' . ($prediction['result'] == 1 ? "High risk of liver disease." : "Low risk of liver disease."), 0, 1);
$pdf->Ln(10);

$patient_name_sanitized = preg_replace('/[^a-zA-Z0-9_]/', '_', $patient['patient_name']);
$file_name = $patient_name_sanitized . '_Liver_Disease_Report.pdf';

// Output the PDF with the dynamic file name
ob_clean(); // Clear any additional output before PDF generation
$pdf->Output('D', $file_name);
exit();
?>
