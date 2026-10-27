<?php
$conn = new mysqli("localhost", "root", "", "home_rental_system");

$email = $_GET['email'] ?? '';
$message = "";

if (isset($_POST['verify'])) {

    $otp = trim($_POST['otp']);

    if ($otp == "") {
        $message = "Enter OTP!";
    } else {

        $query = "SELECT * FROM users WHERE email='$email' AND otp='$otp'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {

            $conn->query("UPDATE users SET is_verified=1, otp=NULL WHERE email='$email'");

            echo "<script>
                alert('Account Verified Successfully!');
                window.location='login.php';
            </script>";

        } else {
            $message = "Invalid OTP!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI;}

body{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
}

/* GLASS BOX */
.container{
    width:350px;
    padding:40px;
    border-radius:15px;
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,0.1);
    text-align:center;
}

/* TITLE */
h2{
    margin-bottom:10px;
    color:#38bdf8;
}

p{
    color:#9ca3af;
    margin-bottom:20px;
}

/* OTP BOX */
.otp-box{
    display:flex;
    justify-content:center;
    gap:10px;
    margin-bottom:20px;
}

.otp-box input{
    width:45px;
    height:55px;
    text-align:center;
    font-size:20px;
    border-radius:8px;
    border:1px solid #374151;
    background:#020617;
    color:#fff;
    outline:none;
}

.otp-box input:focus{
    border-color:#38bdf8;
    box-shadow:0 0 10px rgba(56,189,248,0.4);
}

/* BUTTON */
.btn{
    width:100%;
    padding:12px;
    background:linear-gradient(90deg,#3b82f6,#06b6d4);
    border:none;
    border-radius:8px;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    position:relative;
}

/* LOADER */
.btn.loading{
    pointer-events:none;
    opacity:0.7;
}

.btn.loading::after{
    content:"";
    width:20px;
    height:20px;
    border:3px solid #fff;
    border-top:3px solid transparent;
    border-radius:50%;
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    animation:spin 1s linear infinite;
}

@keyframes spin{
    100%{transform:translate(-50%,-50%) rotate(360deg);}
}

/* MESSAGE */
.error{
    color:#f87171;
    margin-bottom:10px;
}
</style>

</head>

<body>

<div class="container">
    <h2>Verify OTP</h2>
    <p>Enter the 6-digit code</p>

    <?php if($message) echo "<div class='error'>$message</div>"; ?>

    <form method="POST" onsubmit="combineOTP(); showLoader();">

        <div class="otp-box">
            <input type="text" maxlength="1" onkeyup="move(this,1)">
            <input type="text" maxlength="1" onkeyup="move(this,2)">
            <input type="text" maxlength="1" onkeyup="move(this,3)">
            <input type="text" maxlength="1" onkeyup="move(this,4)">
            <input type="text" maxlength="1" onkeyup="move(this,5)">
            <input type="text" maxlength="1" onkeyup="move(this,6)">
        </div>

        <input type="hidden" name="otp" id="finalOtp">

        <button class="btn" id="verifyBtn" name="verify">VERIFY</button>
    </form>
</div>

<script>
function move(current,index){
    let inputs = document.querySelectorAll(".otp-box input");

    if(current.value.length === 1 && index < inputs.length){
        inputs[index].focus();
    }
}

/* COMBINE OTP */
function combineOTP(){
    let inputs = document.querySelectorAll(".otp-box input");
    let otp = "";

    inputs.forEach(input => {
        otp += input.value;
    });

    document.getElementById("finalOtp").value = otp;
}

/* LOADER */
function showLoader(){
    document.getElementById("verifyBtn").classList.add("loading");
}
</script>

</body>
</html>