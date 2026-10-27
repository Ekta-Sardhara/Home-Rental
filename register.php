<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB
$conn = new mysqli("localhost", "root", "", "home_rental_system");
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

$message = "";

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password != $confirm_password) {
        $message = "Passwords do not match!";
    } else {

        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $message = "Email already exists!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $otp = rand(100000,999999);

            $sql = "INSERT INTO users (name,email,password,role,otp,is_verified)
                    VALUES ('$name','$email','$hashed','$role','$otp',0)";

            if ($conn->query($sql)) {

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;

                    $mail->Username = 'sardharaekta@gmail.com';
                    $mail->Password = 'fjzmcpephwhurqar';

                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ];

                    $mail->SMTPDebug = 0;

                    $mail->setFrom('sardharaekta@gmail.com', 'Home Rental');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'OTP Verification';
                    $mail->Body = "
                        <h2>Home Rental System</h2>
                        <p>Your OTP is:</p>
                        <h1 style='color:#38bdf8;'>$otp</h1>
                    ";

                    $mail->send();

                    header("Location: verify_otp.php?email=$email");
                    exit();

                } catch (Exception $e) {
                    $message = "Email not sent: " . $mail->ErrorInfo;
                }

            } else {
                $message = "DB Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - Home Rental</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI;}

body{
    display:flex;
    height:100vh;
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
}

/* GLASS PANEL */
.left{
    width:40%;
    padding:60px;
    backdrop-filter:blur(20px);
    background:rgba(255,255,255,0.05);
    border-right:1px solid rgba(255,255,255,0.1);
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.left h2{ color:#38bdf8; margin-bottom:10px; }
.left p{ color:#9ca3af; margin-bottom:30px; }

.input-box{ position:relative; margin-bottom:20px; }

.input-box input, .input-box select{
    width:100%;
    padding:14px;
    border:1px solid #374151;
    border-radius:8px;
    background:transparent;
    color:#fff;
    outline:none;
}

.input-box label{
    position:absolute;
    top:50%;
    left:12px;
    transform:translateY(-50%);
    color:#9ca3af;
    transition:0.3s;
}

.input-box input:focus + label,
.input-box input:not(:placeholder-shown) + label{
    top:-8px;
    font-size:12px;
    color:#38bdf8;
    background:#020617;
    padding:0 5px;
}

.btn{
    width:100%;
    padding:14px;
    background:linear-gradient(90deg,#3b82f6,#06b6d4);
    border:none;
    border-radius:8px;
    color:#fff;
    font-size:16px;
    cursor:pointer;
}

.right{
    width:60%;
    background:
    linear-gradient(rgba(2,6,23,0.7),rgba(2,6,23,0.9)),
    url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c') no-repeat center;
    background-size:cover;
}

.msg{color:#f87171;margin-bottom:10px;}

.login-link{text-align:center;margin-top:10px;}
.login-link a{color:#38bdf8;text-decoration:none;}

@media(max-width:768px){
    .right{display:none;}
    .left{width:100%;}
}
</style>

</head>

<body>

<div class="left">
    <h2>Create Account</h2>
    <p>Join Home Rental System</p>

    <div class="msg"><?php echo $message; ?></div>

    <form method="POST">

        <div class="input-box">
            <input type="text" name="name" placeholder=" " required>
            <label>Name</label>
        </div>

        <div class="input-box">
            <input type="email" name="email" placeholder=" " required>
            <label>Email</label>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder=" " required>
            <label>Password</label>
        </div>

        <div class="input-box">
            <input type="password" name="confirm_password" placeholder=" " required>
            <label>Confirm Password</label>
        </div>

        <div class="input-box">
            <select name="role">
                <option value="tenant">Tenant</option>
                <option value="owner">Owner</option>
            </select>
        </div>

        <button class="btn">REGISTER</button>
    </form>

    <div class="login-link">
        <p>Already registered?
            <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<div class="right"></div>

</body>
</html>