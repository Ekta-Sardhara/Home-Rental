<?php
$conn = new mysqli("localhost","root","","home_rental_system");

/* PAGINATION */
$limit = 10;
$page = isset($_GET['pno']) ? intval($_GET['pno']) : 1;
$page = max($page,1);
$start = ($page - 1) * $limit;

/* TOTAL */
$total_result = $conn->query("SELECT COUNT(*) as total FROM properties");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

/* DATA */
$p = $conn->query("SELECT * FROM properties LIMIT $start, $limit");

/* SERIAL */
$sr = $start + 1;
?>

<div class="card-box">
<h5>Properties</h5>

<input type="text" id="searchBox" class="form-control mb-3" placeholder="Search...">

<table class="table">
<thead>
<tr>
<th>No</th>
<th>Title</th>
<th>Location</th>
<th>Action</th>
</tr>
</thead>

<tbody id="tableData">

<?php while($row=$p->fetch_assoc()){ ?>
<tr>
<td><?=$sr++?></td>
<td><?=$row['title']?></td>
<td><?=$row['location']?></td>
<td>
<a href="?page=properties&delete=<?=$row['id']?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>

<!-- PAGINATION OUTSIDE TABLE -->
<div id="pagination" style="text-align:center; margin-top:20px;">
<?php for($i=1; $i<=$total_pages; $i++){ ?>
    <a href="?page=properties&pno=<?=$i?>" style="margin:5px; color:#2563eb;">
        <?=$i?>
    </a>
<?php } ?>
</div>

</div>

<script>
function loadData(page){
    let search = document.getElementById("searchBox").value;

    fetch(`search.php?page=properties&search=${search}&p=${page}`)
    .then(res => res.json())
    .then(data => {
        document.getElementById("tableData").innerHTML = data.rows;
        document.getElementById("pagination").innerHTML = data.pagination;
    });
}

/* SEARCH */
document.getElementById("searchBox").addEventListener("keyup", function(e){

    let value = this.value.trim();

    // ENTER + EMPTY → RESET
    if(e.key === "Enter" && value === ""){
        window.location.href = "?page=properties";
        return;
    }

    loadData(1);
});
</script>