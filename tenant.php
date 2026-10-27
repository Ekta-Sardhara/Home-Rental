<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","home_rental_system");

/* PAGINATION */
$limit = 10;

$page_book = isset($_GET['page_book']) ? (int)$_GET['page_book'] : 1;
$offset_book = ($page_book - 1) * $limit;

$page_prop = isset($_GET['page_prop']) ? (int)$_GET['page_prop'] : 1;
$offset_prop = ($page_prop - 1) * $limit;

/* SEARCH */
$search = $conn->real_escape_string($_GET['search'] ?? '');

/* 🔍 SEARCH RESULT */
$search_result = null;
if($search != ''){
    $search_result = $conn->query("
    SELECT p.*, u.name as owner_name
    FROM properties p
    JOIN users u ON p.owner_id = u.id
    WHERE (p.title LIKE '%$search%'
       OR p.location LIKE '%$search%'
       OR u.name LIKE '%$search%')
    LIMIT $limit
    ");
}

/* MY BOOKING */
$my_booking = $conn->query("
SELECT p.*, u.name as owner_name
FROM payments
JOIN properties p ON payments.property_id = p.id
JOIN users u ON p.owner_id = u.id
WHERE payments.tenant_id='{$_SESSION['user_id']}'
LIMIT $limit OFFSET $offset_book
");


/* PROPERTIES */
if($search != ''){
    $properties = $conn->query("
    SELECT p.*, u.name as owner_name
    FROM properties p
    JOIN users u ON p.owner_id = u.id
    LEFT JOIN payments pay ON p.id = pay.property_id
    WHERE (p.title LIKE '%$search%'
       OR p.location LIKE '%$search%'
       OR u.name LIKE '%$search%')
    AND pay.property_id IS NULL
    LIMIT $limit OFFSET $offset_prop
    ");
} else {
    $properties = $conn->query("
    SELECT p.*, u.name as owner_name
    FROM properties p
    JOIN users u ON p.owner_id = u.id
    LEFT JOIN payments pay ON p.id = pay.property_id
    WHERE pay.property_id IS NULL
    ORDER BY p.id DESC
    LIMIT $limit OFFSET $offset_prop
    ");
}

/* TOTALS */
$total_query = ($search != '')
? $conn->query("SELECT COUNT(*) as total FROM properties p JOIN users u ON p.owner_id=u.id WHERE p.title LIKE '%$search%' OR p.location LIKE '%$search%' OR u.name LIKE '%$search%'")
: $conn->query("SELECT COUNT(*) as total FROM properties");

$total_pages = ceil($total_query->fetch_assoc()['total'] / $limit);

$total_book_q = $conn->query("
SELECT COUNT(*) as total
FROM payments
WHERE tenant_id='{$_SESSION['user_id']}'
");
$total_book_pages = ceil($total_book_q->fetch_assoc()['total'] / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Tenant Dashboard</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI;}
body{background:linear-gradient(135deg,#0f172a,#020617);color:#fff;}
.header{display:flex;justify-content:space-between;padding:15px 25px;background:rgba(15,23,42,0.8);}
.container{padding:30px;}
.search-box{margin-bottom:30px;display:flex;justify-content:center;}
.search-box input{width:60%;padding:12px;background:#000;color:#fff;border:1px solid #555;border-radius:6px;}
.search-box button{padding:12px 18px;margin-left:10px;background:#3b82f6;color:#fff;border:none;border-radius:6px;cursor:pointer;}
.title{margin:35px 0 20px;color:#38bdf8;}
.row{display:flex;flex-wrap:wrap;gap:25px;}
.card{width:30%;background:#374151;border-radius:15px;overflow:hidden;}
img{width:100%;height:180px;object-fit:cover;}
.card-content{padding:15px;}
.price{font-weight:bold;display:block;margin-top:8px;}
.btn{padding:8px 12px;border-radius:8px;cursor:pointer;display:inline-block;margin-top:12px;}
.primary{background:#3b82f6;color:#fff;text-decoration:none;}
.success{background:#22c55e;color:#000;}
.pending{background:#facc15;color:#000;}
.rejected{background:#ef4444;color:#fff;}
.pagination{text-align:center;margin:30px 0 50px;}
.pagination a{padding:8px 12px;margin:5px;background:#3b82f6;color:#fff;border-radius:6px;text-decoration:none;}
.pagination a.active{background:#22c55e;font-weight:bold;}
</style>

</head>

<body>

<div class="header">
<h3>🏠 Tenant Dashboard</h3>
<a href="logout.php" class="btn">Logout</a>
</div>

<div class="container">

<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search property..." value="<?php echo htmlspecialchars($search); ?>">
    <input type="hidden" name="page_prop" value="1">
    <button type="submit">Search</button>
</form>

<?php if($search != ''){ ?>
<div class="title">🔍 Search Results</div>
<div class="row">
<?php if($search_result && $search_result->num_rows > 0){
while($p=$search_result->fetch_assoc()){ 

$r = $conn->query("SELECT status FROM requests WHERE tenant_id='{$_SESSION['user_id']}' AND property_id='{$p['id']}'");
$status = ($r->num_rows > 0) ? $r->fetch_assoc()['status'] : null;

$pay = $conn->query("SELECT * FROM payments WHERE tenant_id='{$_SESSION['user_id']}' AND property_id='{$p['id']}'");
$paid = $pay->num_rows > 0;
?>
<div class="card">
<img src="<?php echo $p['image']; ?>">
<div class="card-content">
<h4><?php echo $p['title']; ?></h4>
<p><?php echo $p['location']; ?></p>
<p>👤 Owner: <?php echo $p['owner_name']; ?></p>

<span class="price">₹<?php echo $p['price']; ?></span>

<p>
Status: 
<?php 
if(!$status){ echo "Not Requested"; }
elseif($status == 'pending'){ echo "🟡 Pending"; }
elseif($status == 'accepted'){ echo "🟢 Accepted"; }
else{ echo "🔴 Rejected"; }
?>
</p>

<?php if(!$status){ ?>
<a href="request_property.php?property_id=<?php echo $p['id']; ?>" class="btn primary">Request Property</a>

<?php } elseif($status == 'pending'){ ?>
<div class="btn pending">🟡 Pending</div>

<?php } elseif($status == 'accepted'){ ?>

<?php if(!$paid){ ?>
<a href="pay.php?property_id=<?php echo $p['id']; ?>&amount=<?php echo $p['price']; ?>" class="btn primary">💳 Pay Now</a>
<?php } else { ?>
<div class="btn success">✅ Paid</div>
<?php } ?>

<?php } else { ?>
<div class="btn rejected">🔴 Rejected</div>
<?php } ?>

</div>
</div>
<?php }} else {
echo "<p>No results found.</p>";
} ?>
</div>
<?php } ?>

<div class="title">📌 My Booking</div>
<div class="row">
<?php 
if($my_booking->num_rows > 0){
while($b=$my_booking->fetch_assoc()){
?>
<div class="card">
<img src="<?php echo $b['image']; ?>">
<div class="card-content">
<h4><?php echo $b['title']; ?></h4>
<p><?php echo $b['location']; ?></p>
<p>👤 Owner: <?php echo $b['owner_name']; ?></p>
<span class="price">₹<?php echo $b['price']; ?></span>
<div class="btn success">✅ Booked</div>
</div>
</div>
<?php }} else {
echo "<p>No bookings yet.</p>";
} ?>
</div>

<div class="title">🏡 Properties</div>
<div class="row">
<?php while($p=$properties->fetch_assoc()){ 

$r = $conn->query("SELECT status FROM requests WHERE tenant_id='{$_SESSION['user_id']}' AND property_id='{$p['id']}'");
$status = ($r->num_rows > 0) ? $r->fetch_assoc()['status'] : null;

$pay = $conn->query("SELECT * FROM payments WHERE tenant_id='{$_SESSION['user_id']}' AND property_id='{$p['id']}'");
$paid = $pay->num_rows > 0;
?>
<div class="card">
<img src="<?php echo $p['image']; ?>">
<div class="card-content">
<h4><?php echo $p['title']; ?></h4>
<p><?php echo $p['location']; ?></p>
<p>👤 Owner: <?php echo $p['owner_name']; ?></p>

<span class="price">₹<?php echo $p['price']; ?></span>

<p>
Status: 
<?php 
if(!$status){ echo "Not Requested"; }
elseif($status == 'pending'){ echo "🟡 Pending"; }
elseif($status == 'accepted'){ echo "🟢 Accepted"; }
else{ echo "🔴 Rejected"; }
?>
</p>

<?php if(!$status){ ?>
<a href="request_property.php?property_id=<?php echo $p['id']; ?>" class="btn primary">Request Property</a>

<?php } elseif($status == 'pending'){ ?>
<div class="btn pending">🟡 Pending</div>

<?php } elseif($status == 'accepted'){ ?>

<?php if(!$paid){ ?>
<a href="pay.php?property_id=<?php echo $p['id']; ?>&amount=<?php echo $p['price']; ?>" class="btn primary">💳 Pay Now</a>
<?php } else { ?>
<div class="btn success">✅ Paid</div>
<?php } ?>

<?php } else { ?>
<div class="btn rejected">🔴 Rejected</div>
<?php } ?>

</div>
</div>
<?php } ?>
</div>

<!-- ✅ PAGINATION -->
<div class="pagination">
<?php for($i=1;$i<=$total_pages;$i++){ ?>
<a href="?page_prop=<?php echo $i; ?>&page_book=<?php echo $page_book; ?>&search=<?php echo $search; ?>"
class="<?php echo ($i == $page_prop) ? 'active' : ''; ?>">
<?php echo $i; ?>
</a>
<?php } ?>
</div>

</div>

</body>
</html>