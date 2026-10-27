<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$property_id = $_GET['property_id'];
$amount = $_GET['amount'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#020617);
    font-family:Segoe UI;
    color:#fff;
}

.card{
    max-width:400px;
    margin:80px auto;
    background:rgba(255,255,255,0.05);
    padding:25px;
    border-radius:15px;
    text-align:center;
    backdrop-filter:blur(10px);
    animation:fadeIn 0.5s ease;
}

.amount{
    font-size:26px;
    color:#22c55e;
    margin:10px 0;
}

.qr{
    margin:20px 0;
}

.btn{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:#22c55e;
    color:#000;
    cursor:pointer;
    margin-top:10px;
}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:translateY(0);}
}
</style>

</head>

<body>

<div class="card">

<h3>💳 Pay Rent</h3>

<div class="amount">₹<?php echo $amount; ?></div>

<div class="qr">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=demo@upi&pn=HomeRental&am=<?php echo $amount; ?>&cu=INR">
</div>

<p>Scan & Pay using UPI</p>

<a href="payment_success.php?property_id=<?php echo $property_id; ?>&amount=<?php echo $amount; ?>" class="btn">
✔ I Have Paid
</a>

</div>

</body>
</html>