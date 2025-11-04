<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once "User.php";
$userObj = new User();

$user = ["full_name" => "", "email" => "", "phone" => "", "password" => "", "confirm_password" => ""];
$error = ["full_name" => "", "email" => "", "phone" => "", "password" => "", "confirm_password" => ""];
$submit_error = "";
$submit_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user["full_name"] = trim(htmlspecialchars($_POST["full_name"]));
    $user["email"] = trim(htmlspecialchars($_POST["email"]));
    $user["phone"] = trim(htmlspecialchars($_POST["phone"]));
    $user["password"] = $_POST["password"];
    $user["confirm_password"] = $_POST["confirm_password"];

    // Validation
    if (empty($user["full_name"]))
        $error["full_name"] = "Full name is required";

    if (empty($user["email"]))
        $error["email"] = "Email is required";
    elseif (!filter_var($user["email"], FILTER_VALIDATE_EMAIL))
        $error["email"] = "Invalid email format";

    if (empty($user["phone"]))
        $error["phone"] = "Phone number is required";

    if (empty($user["password"]))
        $error["password"] = "Password is required";
    elseif (strlen($user["password"]) < 6)
        $error["password"] = "Password must be at least 6 characters";

    if (empty($user["confirm_password"]))
        $error["confirm_password"] = "Please confirm your password";
    elseif ($user["password"] != $user["confirm_password"])
        $error["confirm_password"] = "Passwords do not match";

    // If no errors, proceed with registration
    if (empty(array_filter($error))) {
        if ($userObj->emailExists($user["email"])) {
            $submit_error = "Email already exists";
        } else {
            $userObj->full_name = $user["full_name"];
            $userObj->email = $user["email"];
            $userObj->phone = $user["phone"];
            $userObj->password = $user["password"];
            $userObj->role = "customer";

            if ($userObj->register()) {
                $submit_success = "Registration successful! You can now login.";
                $user = ["full_name" => "", "email" => "", "phone" => "", "password" => "", "confirm_password" => ""];
            } else {
                $submit_error = "Registration failed. Please try again.";
            }
        }
    } else {
        $submit_error = "Please fill out all required fields correctly";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Salon Booking System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 450px;
            width: 100%;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .container h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 15px;
            color: #333;
        }

        label span {
            color: red;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
        }

        input:focus {
            border-color: #667eea;
            outline: none;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 4px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        input[type="submit"] {
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>

        <form action="" method="post">
            <label for="full_name">Full Name <span>*</span></label>
            <input type="text" name="full_name" id="full_name" value="<?= $user["full_name"]; ?>">
            <p class="error"><?= $error["full_name"]; ?></p>

            <label for="email">Email <span>*</span></label>
            <input type="email" name="email" id="email" value="<?= $user["email"]; ?>">
            <p class="error"><?= $error["email"]; ?></p>

            <label for="phone">Phone Number <span>*</span></label>
            <input type="text" name="phone" id="phone" value="<?= $user["phone"]; ?>">
            <p class="error"><?= $error["phone"]; ?></p>

            <label for="password">Password <span>*</span></label>
            <input type="password" name="password" id="password">
            <p class="error"><?= $error["password"]; ?></p>

            <label for="confirm_password">Confirm Password <span>*</span></label>
            <input type="password" name="confirm_password" id="confirm_password">
            <p class="error"><?= $error["confirm_password"]; ?></p>

            <input type="submit" value="Register">

            <?php if($submit_error): ?>
                <p class="error" style="text-align: center; margin-top: 10px;"><?= $submit_error; ?></p>
            <?php endif; ?>

            <?php if($submit_success): ?>
                <p class="success"><?= $submit_success; ?></p>
            <?php endif; ?>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>