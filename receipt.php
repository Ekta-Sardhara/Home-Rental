<?php

$conn = new mysqli("localhost","root","","home_rental_system");

$id = intval($_GET['id']);

$res = $conn->query("
SELECT p.*, u.name AS tenant_name, pr.title AS property_title 
FROM payments p
JOIN users u ON p.tenant_id=u.id
JOIN properties pr ON p.property_id=pr.id
WHERE p.id='$id'
");

$data = $res->fetch_assoc();

/* CHECK DOMPDF */
if(!file_exists("vendor/autoload.php")){
    echo "<h3 style='color:red;text-align:center;margin-top:50px;'>
    ❌ Dompdf not installed <br><br>
    Run this command:<br>
    composer require dompdf/dompdf
    </h3>";
    exit();
}

require 'vendor/autoload.php';
use Dompdf\Dompdf;

/* DESIGN */
$html = "
<style>
body{font-family: Arial;}
.box{border:1px solid #ccc;padding:20px;border-radius:10px;}
h2{text-align:center;color:#333;}
p{font-size:14px;}
</style>

<div class='box'>
<h2>🏠 Payment Receipt</h2>

<p><b>Tenant:</b> {$data['tenant_name']}</p>
<p><b>Property:</b> {$data['property_title']}</p>
<p><b>Amount:</b> ₹{$data['amount']}</p>
<p><b>Payment ID:</b> ".($data['payment_id'] ?? 'UPI Payment')."</p>
<p><b>Date:</b> {$data['created_at']}</p>

<hr>
<p style='text-align:center;'>Thank you for your payment!</p>
</div>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("receipt.pdf", ["Attachment"=>1]);