<?php
session_start();

$conn = new mysqli("localhost","root","","home_rental_system");

$tenant_id = $_SESSION['user_id'];
$property_id = $_GET['property_id'];
$amount = $_GET['amount'];

/* PREVENT DUPLICATE PAYMENT */
$check = $conn->query("SELECT * FROM payments WHERE tenant_id='$tenant_id' AND property_id='$property_id'");

if($check->num_rows == 0){
    $conn->query("INSERT INTO payments(tenant_id, property_id, amount, status, method)
    VALUES('$tenant_id','$property_id','$amount','paid','UPI')");
}

$_SESSION['msg'] = "Payment submitted successfully!";
header("Location: tenant.php");
exit();