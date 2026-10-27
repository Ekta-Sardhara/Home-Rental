<?php
$conn = new mysqli("localhost","root","","home_rental_system");

$users = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$properties = $conn->query("SELECT COUNT(*) c FROM properties")->fetch_assoc()['c'];
$requests = $conn->query("SELECT COUNT(*) c FROM requests")->fetch_assoc()['c'];
$payments = $conn->query("SELECT COUNT(*) c FROM payments")->fetch_assoc()['c'];

/* ✅ BOOKED PROPERTIES (from requests) */
$booked = $conn->query("SELECT COUNT(DISTINCT property_id) c FROM requests")->fetch_assoc()['c'];
?>

<div class="row">
<div class="col-md-3"><div class="card-box text-center"><h2><?=$users?></h2><p>Users</p></div></div>
<div class="col-md-3"><div class="card-box text-center"><h2><?=$properties?></h2><p>Properties</p></div></div>
<div class="col-md-3"><div class="card-box text-center"><h2><?=$requests?></h2><p>Requests</p></div></div>
<div class="col-md-3"><div class="card-box text-center"><h2><?=$payments?></h2><p>Payments</p></div></div>
</div>

<!-- ✅ 3 CHARTS -->
<div class="row mt-4">

<!-- 1️⃣ PIE CHART -->
<div class="col-md-4">
<canvas id="pieChart"></canvas>
</div>

<!-- 2️⃣ USERS vs PROPERTIES -->
<div class="col-md-4">
<canvas id="chart1"></canvas>
</div>

<!-- 3️⃣ REQUESTS vs PAYMENTS -->
<div class="col-md-4">
<canvas id="chart2"></canvas>
</div>

</div>

<!-- ✅ CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* 1️⃣ PIE CHART */
new Chart(document.getElementById('pieChart'), {
type:'pie',
data:{
labels:['Total Properties','Booked Properties'],
datasets:[{
data:[<?=$properties?>,<?=$booked?>]
}]
}
});

/* 2️⃣ USERS vs PROPERTIES */
new Chart(document.getElementById('chart1'), {
type:'bar',
data:{
labels:['Users','Properties'],
datasets:[{
data:[<?=$users?>,<?=$properties?>]
}]
}
});

/* 3️⃣ REQUESTS vs PAYMENTS */
new Chart(document.getElementById('chart2'), {
type:'bar',
data:{
labels:['Requests','Payments'],
datasets:[{
data:[<?=$requests?>,<?=$payments?>]
}]
}
});

</script>