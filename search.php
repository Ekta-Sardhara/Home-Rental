<?php
$conn = new mysqli("localhost","root","","home_rental_system");

$page = $_GET['page'] ?? '';
$search = $conn->real_escape_string($_GET['search'] ?? '');

$limit = 10;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$p = max($p,1);
$offset = ($p - 1) * $limit;

if($page == "properties"){

/* COUNT FILTERED */
$count = $conn->query("SELECT COUNT(*) as total FROM properties 
WHERE title LIKE '%$search%' OR location LIKE '%$search%'");

$total = $count->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

/* FETCH FILTERED */
$q = $conn->query("SELECT * FROM properties 
WHERE title LIKE '%$search%' OR location LIKE '%$search%' 
LIMIT $limit OFFSET $offset");

$sr = $offset + 1;

$rows = "";

/* DATA */
if($q->num_rows > 0){

while($r = $q->fetch_assoc()){
$rows .= "<tr>
<td>{$sr}</td>
<td>{$r['title']}</td>
<td>{$r['location']}</td>
<td>
<a href='?page=properties&delete={$r['id']}' class='btn btn-danger btn-sm'>Delete</a>
</td>
</tr>";
$sr++;
}

}else{
$rows .= "<tr>
<td colspan='4' style='text-align:center;color:red;font-weight:bold;'>
Data Not Found
</td>
</tr>";
}

/* PAGINATION HTML */
$pagination = "<div>";

for($i=1;$i<=$totalPages;$i++){
    $pagination .= "<a href='#' onclick='loadData($i)' 
    style='margin:5px; ".($i==$p?"font-weight:bold;color:#2563eb;":"color:#2563eb;")."'>
    $i</a>";
}

$pagination .= "</div>";

echo json_encode([
    "rows" => $rows,
    "pagination" => $pagination
]);

}
?>