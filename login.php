<?php
session_start();
$conn = new mysqli("localhost", "root", "", "home_rental_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ✅ FIXED AUTO LOGIN (NO LOOP)
if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_role']) && isset($_SESSION['user_id'])) {

    $role = strtolower($_COOKIE['user_role']);

    if ($role == "owner") {
        header("Location: owner.php");
        exit();
    } 
    elseif ($role == "tenant") {
        header("Location: tenant.php");
        exit();
    } 
    elseif ($role == "admin") {
        header("Location: admin.php");
        exit();
    }
}

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        if ($row['is_verified'] == 0) {

            $message = "Please verify your email first!";

        } else {

            $role = strtolower($row['role']);
            $dbPassword = $row['password'];

            if ($role == "admin") {

                if ($password === $dbPassword) {

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['role'] = $role;
                    $_SESSION['email'] = $row['email'];

                    setcookie("user_email", $email, time() + 30*24*60*60, "/");
                    setcookie("user_role", $role, time() + 30*24*60*60, "/");

                    header("Location: admin.php");
                    exit();

                } else {

                    $message = "Invalid admin password!";
                }

            } else {

                if (password_verify($password, $dbPassword)) {

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['role'] = $role;
                    $_SESSION['email'] = $row['email'];

                    setcookie("user_email", $email, time() + 30*24*60*60, "/");
                    setcookie("user_role", $role, time() + 30*24*60*60, "/");

                    if ($role == "owner") {
                        header("Location: owner.php");
                        exit();
                    } 
                    elseif ($role == "tenant") {
                        header("Location: tenant.php");
                        exit();
                    }

                } else {

                    $message = "Invalid password!";
                }
            }
        }

    } else {

        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Home Rental</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI;}

body{
    display:flex;
    height:100vh;
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
}

.left{
    width:50%;
    position:relative;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

.left::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c') no-repeat center;
    background-size:cover;
    filter:brightness(0.4);
}

.card{
    position:relative;
    width:280px;
    height:180px;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(15px);
    border-radius:20px;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    animation:float 4s ease-in-out infinite;
}

.card h2{color:#38bdf8;}
.card p{font-size:14px;color:#ccc;}

@keyframes float{
    0%{transform:translateY(0);}
    50%{transform:translateY(-15px);}
    100%{transform:translateY(0);}
}

.right{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
    linear-gradient(rgba(2,6,23,0.8),rgba(2,6,23,0.95)),
    url('https://images.unsplash.com/photo-1560185007-c5ca9d2c014d') no-repeat center;
    background-size:cover;
}

.login-box{
    width:80%;
    max-width:350px;
}

.login-box h2{
    text-align:center;
    margin-bottom:10px;
}

.login-box p{
    text-align:center;
    color:#9ca3af;
    margin-bottom:20px;
}

.input-box{
    position:relative;
    margin-bottom:20px;
}

.input-box input{
    width:100%;
    padding:14px;
    border-radius:8px;
    border:1px solid #374151;
    background:transparent;
    color:#fff;
    outline:none;
}

.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:linear-gradient(90deg,#3b82f6,#06b6d4);
    color:white;
    font-size:16px;
    cursor:pointer;
}

.btn:hover{opacity:0.9;}

.msg{
    text-align:center;
    margin-bottom:15px;
    color:#f87171;
}

.links{
    text-align:center;
    margin-top:15px;
}

.links a{
    color:#38bdf8;
    text-decoration:none;
}

.eye{
    position:absolute;
    right:15px;
    top:14px;
    cursor:pointer;
}

@media(max-width:768px){
    body{flex-direction:column;}
    .left,.right{width:100%;height:50%;}
}
</style>

<script>
function togglePassword(){
    var pass=document.getElementById("password");
    if(pass.type==="password") pass.type="text";
    else pass.type="password";
}
</script>

</head>

<body>

<div class="left">
    <div class="card">
        <h2>🏠 Home Rental System</h2>
        <p>Smart & Easy Living</p>
    </div>
</div>

<div class="right">
    <div class="login-box">

        <h2>Welcome Back</h2>
        <p>Login to continue</p>

        <div class="msg"><?php echo $message; ?></div>

        <form method="POST">
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="eye" onclick="togglePassword()">👁️</span>
            </div>

            <button class="btn" type="submit" name="login">Login</button>
        </form>

        <!-- ✅ ADDED SECTION -->
        <div class="links">

            <p style="color:#9ca3af; margin-bottom:5px;">
                Forgot your password?
            </p>
            <a href="forgot_password.php">Reset Password</a>

            <p style="color:#9ca3af; margin:10px 0 5px;">
                Don't have an account?
            </p>
            <a href="register.php">Create Account</a>

        </div>

    </div>
</div>

</body>
</html>