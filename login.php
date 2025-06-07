<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ABC Library Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            display: flex;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px; /* Adjust as needed */
            max-width: 90%;
        }
        .illustration-side {
            flex: 1;

            background-size: cover;
            background-position: center;
            position: relative;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .illustration-side h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .illustration-side p {
            font-size: 0.9em;
            line-height: 1.5;
            max-width: 80%;
        }
        .login-form-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-side h2 {
            margin-bottom: 5px;
            color: #333;
        }
        .login-form-side p.subtitle {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Ensures padding doesn't increase width */
        }
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9em;
        }
        .options .form-check {
            margin-bottom: 0;
        }
        .options a {
            color: #6a0dad; /* Purple color */
            text-decoration: none;
        }
        .options a:hover {
            text-decoration: underline;
        }
        .btn-login {
            background-color: #6a0dad; /* Purple color */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #52008a; /* Darker purple */
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="illustration-side" style="background-image: url('C:\Users\USER\Pictures\Saved Pictures\cat');"> 
            <div style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 50%); padding-top: 50%;">
                <h2>Welcome to ABC Library Management System</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor.</p>
                <div style="text-align: center; margin-top: 20px;">
                    <span style="display: inline-block; width: 8px; height: 8px; background-color: white; border-radius: 50%; margin: 0 4px;"></span>
                    <span style="display: inline-block; width: 8px; height: 8px; background-color: rgba(255,255,255,0.5); border-radius: 50%; margin: 0 4px;"></span>
                </div>
            </div>
        </div>
        <div class="login-form-side">
            <h2>Login</h2>
            <p class="subtitle">How do I get started digissora ?</p>


            <form method="post">
                <div class="form-group">
                    <label for="username">Email or Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Email or Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="options">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                        <label class="form-check-label" for="remember_me">Remember Me</label>
                    </div>
                    <a href="#">Forgot Password ?</a>
                </div>
                <button name="submit" class="btn-login">Login</button>
            </form>
            <?php
if(isset($_POST["submit"])){
    require("db.php");
    $u = $_POST['username'];
    $p = md5($_POST['password']);
    $sql = "SELECT fullname FROM tbluser WHERE username=? AND PASSWORD=?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $u, $p);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
        $_SESSION['fullname'] = $row['fullname'];
        header("Location: index.php");
        exit();
    } else {
        echo("<p>Invalid username or password!!</p>");
    }
}
?>
        </div>
    </div>
</body>
</html>