<?php
session_start();

/* 🔒 SECURITY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

/* DATABASE */
$conn = new mysqli("localhost","root","","home_rental_system");

/* CHECK PROPERTY ID */
if (!isset($_GET['id'])) {
    header("Location: owner.php");
    exit();
}

$id = $_GET['id'];

/* FETCH PROPERTY */
$result = $conn->query("SELECT * FROM properties WHERE id='$id'");

if($result->num_rows == 0){
    die("Property not found");
}

$p = $result->fetch_assoc();

/* HANDLE REQUEST ACTION */
if(isset($_GET['action']) && isset($_GET['req_id'])){

    $req_id = $_GET['req_id'];

    if($_GET['action']=="accept"){
        $conn->query("UPDATE requests SET status='accepted' WHERE id='$req_id'");
        $conn->query("UPDATE requests SET status='rejected' WHERE property_id='$id' AND id!='$req_id'");
    } else {
        $conn->query("UPDATE requests SET status='rejected' WHERE id='$req_id'");
    }

    header("Location: view_property.php?id=$id");
    exit();
}

/* FETCH REQUESTS */
$r = $conn->query("
SELECT r.*, u.name, u.email 
FROM requests r 
JOIN users u ON r.tenant_id = u.id 
WHERE r.property_id = '$id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Property Details</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
    font-family:Segoe UI;
    padding:6px;
}

/* SMALL CONTAINER */
.container{
    max-width:480px;
    margin:auto;
}

/* 🔥 SMALL GRAY CARD */
.card{
    background:#1f2937;
    border-radius:6px;
    overflow:hidden;
    margin-bottom:8px;
    border:1px solid #374151;
}

/* SMALL IMAGE */
img{
    width:100%;
    height:120px;
    object-fit:cover;
}

/* CONTENT */
.content{
    padding:8px;
}

/* BUTTON */
.btn{
    padding:3px 7px;
    border:none;
    border-radius:4px;
    cursor:pointer;
    margin-top:3px;
    font-size:10px;
    text-decoration:none;
    display:inline-block;
}

.success{background:#22c55e;color:#000;}
.danger{background:#ef4444;color:#fff;}
.primary{background:#3b82f6;color:#fff;}
.warning{background:#facc15;color:#000;}

/* 🔥 SMALL GRAY REQUEST CARD */
.request{
    background:#1f2937;
    border:1px solid #374151;
    padding:6px;
    border-radius:5px;
    margin-bottom:6px;
}

/* TEXT SMALL */
h2{font-size:14px;margin:0;}
h3{font-size:13px;margin-bottom:6px;}
p{font-size:11px;margin:2px 0;}
b{font-size:12px;}

/* FLEX */
.req-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* RESPONSIVE */
@media(max-width:600px){
    img{height:110px;}
    .container{max-width:100%;}
}
</style>

</head>

<body>

<div class="container">

<!-- BACK -->
<div style="margin-bottom:5px;">
<a href="owner.php" class="btn primary">⬅ Back</a>
</div>

<!-- PROPERTY -->
<div class="card">
<img src="<?php echo $p['image']; ?>">
<div class="content">
<h2><?php echo $p['title']; ?></h2>
<p><?php echo $p['location']; ?></p>
<b>₹<?php echo $p['price']; ?></b>
</div>
</div>

<h3>Tenant Requests</h3>

<?php while($x=$r->fetch_assoc()){ ?>

<div class="request">

<div class="req-top">
<div>
<b><?php echo $x['name']; ?></b><br>
<p><?php echo $x['email']; ?></p>
</div>

<div>
<?php
if($x['status']=='accepted') echo "<span style='color:#22c55e;font-size:10px;'>Accepted</span>";
elseif($x['status']=='pending') echo "<span style='color:#facc15;font-size:10px;'>Pending</span>";
else echo "<span style='color:#ef4444;font-size:10px;'>Rejected</span>";
?>
</div>
</div>

<?php
if($x['status'] == 'accepted'){
    $pay = $conn->query("SELECT * FROM payments WHERE property_id='$id' AND tenant_id='".$x['tenant_id']."'");
    
    if($pay->num_rows > 0){
        echo "<div class='btn success'>💰 Paid</div>";
    } else {
        echo "<div class='btn warning'>⏳ Pending</div>";
    }
}
?>

<?php if($x['status']=='pending'){ ?>

<div style="margin-top:3px;">
<a href="view_property.php?id=<?php echo $id; ?>&action=accept&req_id=<?php echo $x['id']; ?>" class="btn success">Accept</a>

<a href="view_property.php?id=<?php echo $id; ?>&action=reject&req_id=<?php echo $x['id']; ?>" class="btn danger">Reject</a>
</div>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>