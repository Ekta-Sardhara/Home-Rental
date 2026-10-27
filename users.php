<?php
$conn = new mysqli("localhost","root","","home_rental_system");

/* ================= AJAX HANDLER ================= */
if(isset($_GET['ajax'])){

$search = $conn->real_escape_string($_GET['search'] ?? '');
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
$limit = 10;
$start = ($page - 1) * $limit;

/* DELETE */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
}

/* TOTAL */
$total_q = $conn->query("SELECT COUNT(*) as total FROM users 
WHERE name LIKE '%$search%' OR email LIKE '%$search%'");

$total = $total_q->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

/* DATA */
$q = $conn->query("SELECT * FROM users 
WHERE name LIKE '%$search%' OR email LIKE '%$search%'
LIMIT $start, $limit");

/* SERIAL */
$sr = $start + 1;

/* TABLE */
echo '<table class="table">
<thead>
<tr>
<th>No</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>
</thead>
<tbody>';

if($q->num_rows == 0){
echo "<tr><td colspan='4' style='text-align:center;'>No data found</td></tr>";
}

while($r = $q->fetch_assoc()){
echo "<tr>
<td>".$sr++."</td>
<td>".$r['name']."</td>
<td>".$r['email']."</td>
<td>
<button onclick='deleteUser(".$r['id'].")' class='btn btn-delete btn-sm'>Delete</button>
</td>
</tr>";
}

echo '</tbody></table>';

/* ✅ SIMPLE PAGINATION (LIKE PROPERTY PAGE) */
echo '<div style="text-align:center; margin-top:15px;">';

for($i=1; $i<=$total_pages; $i++){

$style = ($i == $page) 
? "font-weight:bold;color:#2563eb;" 
: "color:#2563eb;";

echo "<a href='#' onclick='loadData($i)' style='margin:5px; $style'>
$i
</a>";
}

echo '</div>';

exit();
}
?>

<!-- ================= UI ================= -->
<div class="card-box">
<h5>Users</h5>

<input type="text" id="searchBox" class="form-control mb-3" placeholder="Search...">

<div id="tableArea"></div>

</div>

<script>
let currentSearch = "";

/* LOAD DATA */
function loadData(page=1){
    fetch(`users.php?ajax=1&search=${currentSearch}&p=${page}`)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tableArea").innerHTML = data;
    });
}

/* DELETE */
function deleteUser(id){
    if(confirm("Delete this user?")){
        fetch(`users.php?ajax=1&delete=${id}`)
        .then(()=>loadData());
    }
}

/* SEARCH */
document.getElementById("searchBox").onkeyup = function(){
    currentSearch = this.value;
    loadData(1);
}

/* FIRST LOAD */
loadData();
</script>