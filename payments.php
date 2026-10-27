<?php
$conn = new mysqli("localhost","root","","home_rental_system");

/* ================= PAGINATION ================= */
$limit = 10;
$page = isset($_GET['pno']) ? intval($_GET['pno']) : 1;
$page = max($page,1);
$start = ($page - 1) * $limit;

/* ================= SEARCH ================= */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

/* ================= TOTAL ================= */
if($search != ""){
    $total_result = $conn->query("
        SELECT COUNT(*) as total 
        FROM payments pay
        LEFT JOIN users u ON pay.tenant_id = u.id
        LEFT JOIN properties p ON pay.property_id = p.id
        WHERE u.name LIKE '%$search%' 
           OR p.title LIKE '%$search%' 
           OR pay.amount LIKE '%$search%'
           OR pay.status LIKE '%$search%'
    ");
}else{
    $total_result = $conn->query("SELECT COUNT(*) as total FROM payments");
}

$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

/* ================= DATA ================= */
if($search != ""){
    // 🔍 SEARCH → SHOW ALL RESULTS
    $p = $conn->query("
        SELECT pay.id, u.name AS user_name, p.title AS property_name, pay.amount, pay.status
        FROM payments pay
        LEFT JOIN users u ON pay.tenant_id = u.id
        LEFT JOIN properties p ON pay.property_id = p.id
        WHERE u.name LIKE '%$search%' 
           OR p.title LIKE '%$search%' 
           OR pay.amount LIKE '%$search%'
           OR pay.status LIKE '%$search%'
    ");
}else{
    // 📄 PAGINATION MODE
    $p = $conn->query("
        SELECT pay.id, u.name AS user_name, p.title AS property_name, pay.amount, pay.status
        FROM payments pay
        LEFT JOIN users u ON pay.tenant_id = u.id
        LEFT JOIN properties p ON pay.property_id = p.id
        LIMIT $start, $limit
    ");
}

/* SERIAL */
$sr = $start + 1;
?>

<div class="card-box">
<h5>Payments</h5>

<input type="text" id="searchBox" class="form-control mb-3" placeholder="Search..."
style="background:#374151; color:#fff; border:1px solid #9ca3af;">

<!-- ================= TABLE ================= -->
<div id="tableArea">

<table class="table">

<thead>
<tr>
<th>No</th>
<th>Name</th>
<th>Amount</th>
<th>Status</th>
</tr>
</thead>

<tbody>
<?php while($row=$p->fetch_assoc()){ ?>
<tr>

<td><?=$sr++?></td>

<td>
<?=$row['user_name']?> <br>
<small><?=$row['property_name']?></small>
</td>

<td><?=$row['amount']?></td>

<td>
<?php
$status = strtolower($row['status']);
$color = "gray";

if($status=="paid" || $status=="completed") $color="green";
elseif($status=="failed") $color="red";
elseif($status=="pending") $color="orange";
?>
<span style="color:<?=$color?>; font-weight:bold;">
<?=$row['status']?>
</span>
</td>

</tr>
<?php } ?>
</tbody>

</table>

</div>

<!-- ================= PAGINATION (PROPERTY STYLE, ACTIVE BLUE UNDERLINE) ================= -->
<?php if($search == ""){ ?>
<div style="text-align:center; margin-top:15px;">
<?php for($i = 1; $i <= $total_pages; $i++){ ?>
    <a href="?page=payments&pno=<?=$i?>" 
       style="margin:0 5px; text-decoration:none; 
              <?=($i==$page) ? 'border-bottom:2px solid #2563eb; color:#2563eb;' : 'color:#2563eb;'?>">
        <?=$i?>
    </a>
<?php } ?>
</div>
<?php } ?>

</div>

<!-- ================= SCRIPT ================= -->
<script>
const searchBox = document.getElementById("searchBox");

/* AJAX SEARCH */
function loadData(){
    let value = searchBox.value.trim();

    fetch(`?page=payments&search=${value}`)
    .then(r => r.text())
    .then(d => {
        document.getElementById("tableArea").innerHTML =
            new DOMParser().parseFromString(d, "text/html")
            .getElementById("tableArea").innerHTML;
    });
}

searchBox.addEventListener("keyup", function(e){
    let value = this.value.trim();

    // RESET
    if(e.key === "Enter" && value === ""){
        window.location.href = "?page=payments";
        return;
    }

    // LIVE SEARCH
    loadData();
});
</script>