<?php
session_start();
include('config.php'); // Database connection

if(isset($_POST['submit'])) {
    $patient_id = $_POST['patient_id'];
    $predictor = $_POST['predictor']; // get the predictor type

    // Check if patient ID exists in the patient table
    $query = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $_SESSION['patient_id'] = $patient_id;
        header("Location: ${predictor}_prediction.php"); // Redirect to respective predictor page
    } else {
        $error = "Patient ID not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Patient ID</title>
    <style>
        /* General Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    background-image: url("images/patient.png");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover; /* Ensures the image fills the entire page */
}
body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Change color and opacity */
    z-index: -1;
}
/* Verification Container */
.verification-container {
    padding: 40px 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.verification-container:hover {
    transform: translateY(-5px);
    box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.3);
}

.verification-container h2 {
    color: #4c6ef5;
    font-size: 28px;
    margin-bottom: 20px;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form input[type="number"] {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #d0d5db;
    outline: none;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

form input[type="number"]:focus {
    border-color: #4c6ef5;
}

button[type="submit"] {
    padding: 12px;
    border: none;
    border-radius: 8px;
    background-color: #4c6ef5;
    color: #ffffff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #3b5bdb;
}

/* Error Message */
.error {
    color: #e53e3e;
    font-size: 14px;
    margin-top: 10px;
}

/* Responsive Design */
@media (max-width: 500px) {
    .verification-container {
        padding: 30px 20px;
        width: 90%;
    }
}

        </style>
</head>
<body>
    <div class="verification-container">
    
        <form action="" method="post">
            <input type="number" name="patient_id" placeholder="Patient ID" required>
            <input type="hidden" name="predictor" value="<?php echo $_GET['predictor']; ?>">
            <button type="submit" name="submit">Submit</button>
            <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
