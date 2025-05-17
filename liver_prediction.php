<?php
session_start();
if(!isset($_SESSION['patient_id'])) {
    header("Location: verify_patient.php?predictor=liver");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liver Disease Prediction</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-image: url("images/liver.png");
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
        <h2>Liver Disease Prediction</h2>
        <form action="test_liver.php" method="post" class="prediction-form">
            <div class="form-group">
                <label title="Age of patient">Age:</label>
                <input type="number" name="age" required oninput="validateField(this, 0, 120)">
                <span id="age-warning" class="warning"></span>

                <label title="Gender of patient (1 = Male, 0 = Female)">Gender:</label>
                <input type="number" name="gender" required oninput="validateField(this, 0, 1)">
                <span id="gender-warning" class="warning"></span>
            </div>
            
            <div class="form-group">
                <label title="Total Bilirubin level">Total Bilirubin:</label>
                <input type="number" step="0.1" name="total_bilirubin" required oninput="validateField(this, 0.1, 30)">
                <span id="total_bilirubin-warning" class="warning"></span>

                <label title="Direct Bilirubin level">Direct Bilirubin:</label>
                <input type="number" step="0.1" name="direct_bilirubin" required oninput="validateField(this, 0, 5)">
                <span id="direct_bilirubin-warning" class="warning"></span>
            </div>

            <div class="form-group">
                <label title="Alkaline Phosphatase level">Alkaline Phosphotase:</label>
                <input type="number" name="alkaline_phosphotase" required oninput="validateField(this, 40, 2000)">
                <span id="alkaline_phosphotase-warning" class="warning"></span>

                <label title="Alamine Aminotransferase level">Alamine Aminotransferase:</label>
                <input type="number" name="alamine_aminotransferase" required oninput="validateField(this, 0, 1000)">
                <span id="alamine_aminotransferase-warning" class="warning"></span>
            </div>

            <div class="form-group">
                <label title="Aspartate Aminotransferase level">Aspartate Aminotransferase:</label>
                <input type="number" name="aspartate_aminotransferase" required oninput="validateField(this, 0, 1000)">
                <span id="aspartate_aminotransferase-warning" class="warning"></span>

                <label title="Total Proteins level">Total Proteins:</label>
                <input type="number" step="0.1" name="total_protiens" required oninput="validateField(this, 0, 10)">
                <span id="total_protiens-warning" class="warning"></span>
            </div>

            <div class="form-group">
                <label title="Albumin level">Albumin:</label>
                <input type="number" step="0.1" name="albumin" required oninput="validateField(this, 0, 5)">
                <span id="albumin-warning" class="warning"></span>

                <label title="Albumin and Globulin Ratio">Albumin and Globulin Ratio:</label>
                <input type="number" step="0.1" name="albumin_and_globulin_ratio" required oninput="validateField(this, 0.1, 3)">
                <span id="albumin_and_globulin_ratio-warning" class="warning"></span>
            </div>

            <button type="submit">Test Disease</button>
        </form>
    </div>
</body>
</html>
