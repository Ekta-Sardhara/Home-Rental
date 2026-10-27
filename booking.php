<?php
$conn = new mysqli("localhost", "root", "", "home_rental_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

/* 📄 PAGINATION */
$limit = 10;
$page = isset($_GET['pno']) ? intval($_GET['pno']) : 1;
$page = max($page,1);
$start = ($page - 1) * $limit;

/* 🔍 SEARCH */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

/* ================= TOTAL ================= */
if($search != ""){
    $total_result = $conn->query("
        SELECT COUNT(*) as total FROM properties p
        WHERE (SELECT COUNT(*) FROM requests r WHERE r.property_id=p.id AND r.status='accepted') = 0
        AND (p.title LIKE '%$search%' OR p.location LIKE '%$search%')
    ");
}else{
    $total_result = $conn->query("
        SELECT COUNT(*) as total FROM (
            SELECT p.id 
            FROM properties p
            WHERE (SELECT COUNT(*) FROM requests r WHERE r.property_id=p.id AND r.status='accepted') = 0
        ) as temp
    ");
}

$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

/* ================= DATA ================= */
if($search != ""){

    // SEARCH (NO PAGINATION)
    $properties = $conn->query("
        SELECT p.* 
        FROM properties p
        WHERE (SELECT COUNT(*) FROM requests r WHERE r.property_id=p.id AND r.status='accepted') = 0
        AND (p.title LIKE '%$search%' OR p.location LIKE '%$search%')
        ORDER BY p.id DESC
    ");

}else{

    // NORMAL PAGINATION
    $properties = $conn->query("
        SELECT p.* 
        FROM properties p
        WHERE (SELECT COUNT(*) FROM requests r WHERE r.property_id=p.id AND r.status='accepted') = 0
        ORDER BY p.id DESC
        LIMIT $start, $limit
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking - Home Rental System</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
    font-family:Segoe UI;
    margin:0;
}

.top-nav{
    background:rgba(255,255,255,0.05);
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
}

.top-nav a{
    color:#fff;
    margin-left:15px;
    text-decoration:none;
}

.header{
    text-align:center;
    margin:30px 0;
}

.cards-container{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:20px;
    padding:20px;
}

.property-card{
    width:260px;
    background:rgba(255,255,255,0.05);
    border-radius:12px;
    padding:12px;
    text-align:center;
    cursor:pointer;
}

.property-card img{
    width:100%;
    height:160px;
    object-fit:cover;
    border-radius:8px;
}

.status-available{
    color:#22c55e;
    font-weight:bold;
}

/* SEARCH BOX */
.search-box{
    display:block;
    margin:0 auto;
    padding:10px;
    width:250px;
    border-radius:6px;
    border:1px solid #555;
    background:#111827;
    color:#fff;
}

/* PAGINATION */
.pagination{
    text-align:center;
    margin-top:20px;
}

.pagination a{
    margin:5px;
    text-decoration:none;
    color:#2563eb;
}

.pagination a.active{
    font-weight:bold;
}
</style>

</head>

<body>

<div class="top-nav">
    <h1>Home Rental System</h1>
    <div>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="header">
    <h1>Available Properties</h1>
    <p>Click any property to continue booking</p>
</div>

<!-- 🔍 SEARCH -->
<form method="GET" style="text-align:center;">
    <input type="hidden" name="pno" value="1">
    <input type="text" name="search" class="search-box" 
        placeholder="Search property..." 
        value="<?php echo $search; ?>">
</form>

<div class="cards-container">

<?php while($p = $properties->fetch_assoc()){ ?>
    
    <div class="property-card">
        
        <img src="<?php echo $p['image'] ?? 'uploads/default.png'; ?>">

        <h4><?php echo $p['title']; ?></h4>
        <p><?php echo $p['location']; ?></p>
        <p>₹<?php echo $p['price']; ?></p>

        <p class="status-available">Available</p>

    </div>

<?php } ?>

</div>

<!-- 📄 PAGINATION (ONLY WHEN NO SEARCH) -->
<?php if($search == ""){ ?>
<div class="pagination">
<?php for($i=1; $i<=$total_pages; $i++){ ?>
    <a href="?pno=<?php echo $i; ?>"
       class="<?php echo ($i == $page) ? 'active' : ''; ?>">
       <?php echo $i; ?>
    </a>
<?php } ?>
</div>
<?php } ?>

</body>
</html>