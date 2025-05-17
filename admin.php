<?php
session_start();
include('config.php'); // for DB connection

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // hashed password

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: homepage.php");
    } else {
        $error = "Invalid Username or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        /* General Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    background-image: url("images/admin.png");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover; /* Ensures the image fills the entire page */
    align-items: center;
    min-height: 100vh;
    margin: 0;
    
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

/* Login Container */
.login-container {
    
    padding: 40px 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-container:hover {
    transform: translateY(-5px);
    box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.3);
}

.login-container h2 {
    color: white;
    font-size: 28px;
    margin-bottom: 20px;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form input {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #d0d5db;
    outline: none;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

form input:focus {
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
    .login-container {
        padding: 30px 20px;
        width: 90%;
    }
}

        </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
