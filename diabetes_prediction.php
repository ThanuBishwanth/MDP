<?php
session_start();
if(!isset($_SESSION['patient_id'])) {
    header("Location: verify_patient.php?predictor=diabetes");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diabetes Prediction</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-image: url("images/diabetes.jpg");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover; /* Ensures the image fills the entire page */
        }

    </style>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        function validateField(field, min, max) {
            const value = parseFloat(field.value);
            const warning = document.getElementById(field.name + '-warning');
            if (value < min || value > max) {
                warning.innerText = `Please enter a value between ${min} and ${max}.`;
            } else {
                warning.innerText = '';
            }
        }
    </script>
</head>
<body>
    <div class="predictor-container">
        <h2>Diabetes Prediction</h2>
        <form action="test_diabetes.php" method="post" class="prediction-form">
            <div class="form-group">
                <label class="info-icon" title="Number of times pregnant">Pregnancies:</label>
                <input type="number" name="pregnancies" required oninput="validateField(this, 0, 20)">
                <span id="pregnancies-warning" class="warning"></span>

                <label class="info-icon" title="Plasma glucose concentration">Glucose:</label>
                <input type="number" name="glucose" required oninput="validateField(this, 0, 300)">
                <span id="glucose-warning" class="warning"></span>
            </div>
            
            <div class="form-group">
                <label class="info-icon" title="Diastolic blood pressure">Blood Pressure:</label>
                <input type="number" name="bloodpressure" required oninput="validateField(this, 0, 200)">
                <span id="bloodpressure-warning" class="warning"></span>

                <label class="info-icon" title="Triceps skinfold thickness">Skin Thickness:</label>
                <input type="number" name="skinthickness" required oninput="validateField(this, 0, 100)">
                <span id="skinthickness-warning" class="warning"></span>
            </div>

            <div class="form-group">
                <label class="info-icon" title="2-Hour serum insulin">Insulin:</label>
                <input type="number" name="insulin" required oninput="validateField(this, 0, 900)">
                <span id="insulin-warning" class="warning"></span>

                <label class="info-icon" title="Body mass index">BMI:</label>
                <input type="number" step="0.1" name="bmi" required oninput="validateField(this, 10, 70)">
                <span id="bmi-warning" class="warning"></span>
            </div>

            <div class="form-group">
                <label class="info-icon" title="Diabetes pedigree function">Diabetes Pedigree Function:</label>
                <input type="number" step="0.01" name="diabetespedigreefunction" required oninput="validateField(this, 0.00, 2.500)">
                <span id="diabetespedigreefunction-warning" class="warning"></span>

                <label class="info-icon" title="Age of patient">Age:</label>
                <input type="number" name="age" required oninput="validateField(this, 0, 120)">
                <span id="age-warning" class="warning"></span>
            </div>
            
            <button type="submit">Test Disease</button>
        </form>
    </div>
</body>
</html>
