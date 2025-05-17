<?php
session_start();
if(!isset($_SESSION['patient_id'])) {
    header("Location: verify_patient.php?predictor=heart");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Heart Disease Prediction</title>
    <style>
        /* General Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    background-image: url("images/heart.png");
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
        <h2>Heart Disease Prediction</h2>
        <form action="test_heart.php" method="post" class="prediction-form">
            <div class="form-group">
                <label class="info-icon" title="Age of patient">Age:</label>
                <input type="number" name="age" required oninput="validateField(this, 0, 120)">
                <span id="age-warning" class="warning"></span>

                <label class="info-icon" title="Gender of patient">Sex (1 = Male, 0 = Female):</label>
                <input type="number" name="sex" required oninput="validateField(this, 0, 1)">
                <span id="sex-warning" class="warning"></span>
            </div>
            
            <div class="form-group">
                <label>Chest Pain Type (cp):</label>
                <input type="number" name="cp" required oninput="validateField(this, 0, 3)">
                <span id="cp-warning" class="warning"></span>

                <label>Resting Blood Pressure (trestbps):</label>
                <input type="number" name="trestbps" required oninput="validateField(this, 90, 200)">
                <span id="trestbps-warning" class="warning"></span>
            </div>

            <div class="form-group">
            
            <label>Cholesterol (chol):</label>
            <input type="number" name="chol" required oninput="validateField(this, 100, 600)">
            <span id="chol-warning" class="warning"></span>

            <label>Fasting Blood Sugar (fbs, 1 = True; 0 = False):</label>
            <input type="number" name="fbs" required oninput="validateField(this, 0, 1)">
            <span id="fbs-warning" class="warning"></span>
            </div>
            <div class="form-group">
            <label>Resting ECG (restecg):</label>
            <input type="number" name="restecg" required oninput="validateField(this, 0, 2)">
            <span id="restecg-warning" class="warning"></span>

            <label>Max Heart Rate Achieved (thalach):</label>
            <input type="number" name="thalach" required oninput="validateField(this, 70, 210)">
            <span id="thalach-warning" class="warning"></span>
            </div>
            <div class="form-group">

            <label>Exercise Induced Angina (exang, 1 = Yes; 0 = No):</label>
            <input type="number" name="exang" required oninput="validateField(this, 0, 1)">
            <span id="exang-warning" class="warning"></span>

            <label>Oldpeak:</label>
            <input type="number" step="0.1" name="oldpeak" required oninput="validateField(this, 0, 10)">
            <span id="oldpeak-warning" class="warning"></span>
            </div>
            <div class="form-group">

            <label>Slope:</label>
            <input type="number" name="slope" required oninput="validateField(this, 0, 2)">
            <span id="slope-warning" class="warning"></span>

            <label>Number of Major Vessels (ca):</label>
            <input type="number" name="ca" required oninput="validateField(this, 0, 3)">
            <span id="ca-warning" class="warning"></span>
            </div>
            <div class="form-group">

            <label>Thalassemia (thal):</label>
            <input type="number" name="thal" required oninput="validateField(this, 0, 3)">
            <span id="thal-warning" class="warning"></span>
            </div>

            <!-- Repeat the above structure for each field pair -->
            
            <button type="submit">Test Disease</button>
        </form>
    </div>
</body>
</html>
