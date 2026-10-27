<?php
session_start();
$conn = new mysqli("localhost","root","","home_rental_system");

$property_id = $_GET['property_id'];
$amount = $_GET['amount'];
$tenant_id = $_SESSION['user_id'];

$payment_id = "PAY".rand(1000,9999);

// جلوگیری duplicate payment
$check = $conn->query("SELECT * FROM payments 
WHERE tenant_id='$tenant_id' AND property_id='$property_id'");

if($check->num_rows == 0){

    $conn->query("INSERT INTO payments(tenant_id, property_id, amount, status, payment_id)
    VALUES('$tenant_id','$property_id','$amount','paid','$payment_id')");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Success</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
    font-family:Segoe UI;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:rgba(255,255,255,0.05);
    padding:30px;
    border-radius:15px;
    text-align:center;
    animation:pop 0.4s ease;
}

.success{
    font-size:22px;
    color:#22c55e;
    margin-bottom:10px;
}

.btn{
    margin-top:15px;
    padding:10px 15px;
    border:none;
    border-radius:8px;
    background:#3b82f6;
    color:#fff;
    cursor:pointer;
}

@keyframes pop{
    from{transform:scale(0.7);opacity:0;}
    to{transform:scale(1);opacity:1;}
}
</style>

<script>
setTimeout(()=>{
    alert("✅ Payment Successful!");
},300);
</script>

</head>

<body>

<div class="box">
<div class="success">Payment Successful 🎉</div>
<p>Your payment has been recorded.</p>

<a href="tenant.php" class="btn">Go to Dashboard</a>
</div>

</body>
</html>