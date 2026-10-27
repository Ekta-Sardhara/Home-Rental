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
        SELECT COUNT(*) as total FROM requests r
        LEFT JOIN users u ON r.tenant_id = u.id
        LEFT JOIN properties p ON r.property_id = p.id
        WHERE u.name LIKE '%$search%' 
           OR p.title LIKE '%$search%' 
           OR r.status LIKE '%$search%'
    ");
}else{
    $total_result = $conn->query("SELECT COUNT(*) as total FROM requests");
}

$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

/* ================= DATA ================= */
if($search != ""){

    // SEARCH MODE (ALL RESULTS)
    $r = $conn->query("
        SELECT r.id, u.name AS user_name, p.title AS property_name, r.status
        FROM requests r
        LEFT JOIN users u ON r.tenant_id = u.id
        LEFT JOIN properties p ON r.property_id = p.id
        WHERE u.name LIKE '%$search%' 
           OR p.title LIKE '%$search%' 
           OR r.status LIKE '%$search%'
    ");

}else{

    // NORMAL PAGINATION
    $r = $conn->query("
        SELECT r.id, u.name AS user_name, p.title AS property_name, r.status
        FROM requests r
        LEFT JOIN users u ON r.tenant_id = u.id
        LEFT JOIN properties p ON r.property_id = p.id
        LIMIT $start, $limit
    ");
}

/* SERIAL */
$sr = $start + 1;
?>

<!-- ================= UI ================= -->
<div class="card-box">
<h5>Requests</h5>

<input type="text" id="searchBox" class="form-control mb-3" placeholder="Search..." 
style="background:#374151; color:#fff; border:1px solid #9ca3af;">

<!-- TABLE AREA -->
<div id="tableArea">

<table class="table">

<thead>
<tr>
<th>No</th>
<th>Name</th>
<th>Status</th>
</tr>
</thead>

<tbody>
<?php while($row=$r->fetch_assoc()){ ?>
<tr>

<td><?=$sr++?></td>

<td>
<?=$row['user_name']?> <br>
<small><?=$row['property_name']?></small>
</td>

<td>
<?php
$status = strtolower($row['status']);
$color = "gray";

if($status=="approved" || $status=="accepted") $color="green";
elseif($status=="rejected") $color="red";
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

<!-- ================= PAGINATION ================= -->
<?php if($search == ""){ ?>
<div style="text-align:center; margin-top:15px;">
<?php for($i=1; $i<=$total_pages; $i++){ ?>
    <a href="?page=requests&pno=<?=$i?>" 
       style="margin:5px; <?=($i==$page)?'font-weight:bold;color:#2563eb;':'color:#2563eb;'?>">
       <?=$i?>
    </a>
<?php } ?>
</div>
<?php } ?>

</div>

<!-- ================= SCRIPT ================= -->
<script>
const searchBox = document.getElementById("searchBox");

/* LOAD FUNCTION */
function loadData(page=1){
    let value = searchBox.value.trim();

    fetch(`?page=requests&search=${value}&pno=${page}`)
    .then(r => r.text())
    .then(d => {
        document.getElementById("tableArea").innerHTML = 
            new DOMParser().parseFromString(d, "text/html")
            .getElementById("tableArea").innerHTML;
    });
}

/* SEARCH */
searchBox.addEventListener("keyup", function(e){
    let value = this.value.trim();

    // ENTER + EMPTY → RESET
    if(e.key === "Enter" && value === ""){
        window.location.href = "?page=requests";
        return;
    }

    // LIVE SEARCH
    loadData(1);
});
</script>