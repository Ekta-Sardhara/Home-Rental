<?php
session_start();

/* SECURITY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

/* AUTO LOGOUT */
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_destroy();
    header("Location: login.php");
    exit();
}

/* DB */
$conn = new mysqli("localhost","root","","home_rental_system");

/* DELETE */
if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    $conn->query("DELETE FROM requests WHERE property_id='$id'");
    $conn->query("DELETE FROM properties WHERE id='$id'");
    $_SESSION['msg']="Deleted!";
    header("Location: owner.php"); 
    exit();
}

/* UPDATE */
if(isset($_POST['update_property'])){
    $id=$_POST['id'];

    $check=$conn->query("SELECT * FROM requests WHERE property_id='$id' AND status='accepted'");
    if($check->num_rows>0){
        $_SESSION['msg']="❌ Cannot edit! Request already accepted.";
        header("Location: owner.php"); 
        exit();
    }

    if($_FILES['image']['name']!=""){
        $target="uploads/";
        if(!is_dir($target)) mkdir($target);

        $img=time()."_".basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],$target.$img);

        $conn->query("UPDATE properties 
        SET title='{$_POST['title']}',location='{$_POST['location']}',price='{$_POST['price']}',image='$target$img' 
        WHERE id='$id'");
    } else {
        $conn->query("UPDATE properties 
        SET title='{$_POST['title']}',location='{$_POST['location']}',price='{$_POST['price']}' 
        WHERE id='$id'");
    }

    $_SESSION['msg']="Updated!";
    header("Location: owner.php"); 
    exit();
}

/* PAGINATION */
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

/* FETCH */
$properties = $conn->query("
SELECT * FROM properties 
WHERE owner_id='{$_SESSION['user_id']}' 
ORDER BY id DESC 
LIMIT $limit OFFSET $offset
");

/* TOTAL */
$total_q = $conn->query("
SELECT COUNT(*) as total 
FROM properties 
WHERE owner_id='{$_SESSION['user_id']}'
");
$total_row = $total_q->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Owner Dashboard</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
    font-family:Segoe UI;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 25px;
    background:rgba(15,23,42,0.8);
}

.header h3{
    margin:0;
    color:#38bdf8;
}

.header .btn{
    color:#fff;
    background:#ef4444;
    padding:6px 12px;
    border-radius:6px;
}

.container{padding:20px;}

.card{
    background:#374151;
    border-radius:15px;
    margin-bottom:20px;
}

img{width:100%;height:180px;object-fit:cover;}

.btn{
    padding:8px 12px;
    border-radius:8px;
    margin-top:5px;
    display:inline-block;
    text-decoration:none;
}

.success{background:#22c55e;color:#000;}
.danger{background:#ef4444;color:#fff;}
.primary{background:#3b82f6;color:#fff;}
.warning{background:#facc15;color:#000;}

.row{display:flex;flex-wrap:wrap;gap:20px;}
.col{width:30%;}

input{
    width:100%;
    padding:8px;
    margin:5px 0;
    background:transparent;
    border:1px solid #444;
    color:#fff;
    border-radius:6px;
}

.payment-line{margin:10px 0;}
.button-line{display:flex;gap:8px;flex-wrap:wrap;}

/* ✅ UPDATED PAGINATION STYLE (BOOKING STYLE) */
.pagination{
    text-align:center;
    margin-top:25px;
}

.pagination a{
    margin:5px;
    text-decoration:none;
    color:#2563eb;
}

.pagination a.active{
    font-weight:bold;
    text-decoration:underline;
}
</style>

<script>
function toggle(id){
    let el=document.getElementById(id);
    el.style.display=(el.style.display=='none')?'block':'none';
}

function confirmDelete(){
    return confirm("Are you sure you want to delete this property?");
}

function confirmUpdate(){
    return confirm("Are you sure you want to update this property?");
}
</script>

</head>

<body>

<div class="header">
<h3>🏠 Home Rental System</h3>
<a href="logout.php" class="btn">Logout</a>
</div>

<div class="container">

<?php 
if(isset($_SESSION['msg'])){
    echo "<p>".$_SESSION['msg']."</p>";
    unset($_SESSION['msg']);
}
?>

<div style="margin-bottom:15px;">
<a href="add_property.php" class="btn success">+ Add Property</a>
</div>

<div class="row">

<?php while($p=$properties->fetch_assoc()){ ?>

<?php
$accepted=$conn->query("SELECT * FROM requests WHERE property_id='".$p['id']."' AND status='accepted'");
$editable=($accepted->num_rows==0);
$payment=$conn->query("SELECT * FROM payments WHERE property_id='".$p['id']."'");
?>

<div class="col">
<div class="card">

<img src="<?php echo $p['image']; ?>">

<div style="padding:10px">
<h4><?php echo $p['title']; ?></h4>
<p><?php echo $p['location']; ?></p>
<b>₹<?php echo $p['price']; ?></b><br>

<div class="payment-line">
<?php
if($accepted->num_rows > 0){
    if($payment->num_rows > 0){
        echo "<span class='btn success'>💰 Payment Done</span>";
    } else {
        echo "<span class='btn warning'>⏳ Payment Pending</span>";
    }
}
?>
</div>

<div class="button-line">
<?php if($editable){ ?>
<button onclick="toggle('edit<?php echo $p['id']; ?>')" class="btn warning">Edit</button>
<?php } else { ?>
<button class="btn warning" disabled>Locked</button>
<?php } ?>

<a href="?delete=<?php echo $p['id']; ?>" class="btn danger" onclick="return confirmDelete()">Delete</a>

<a href="view_property.php?id=<?php echo $p['id']; ?>" class="btn primary">View Details</a>
</div>

<div id="edit<?php echo $p['id']; ?>" style="display:none">
<form method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
<input type="hidden" name="id" value="<?php echo $p['id']; ?>">
<input name="title" value="<?php echo $p['title']; ?>">
<input name="location" value="<?php echo $p['location']; ?>">
<input name="price" value="<?php echo $p['price']; ?>">
<input type="file" name="image">
<button name="update_property" class="btn success">Update</button>
</form>
</div>

</div>
</div>
</div>

<?php } ?>

</div>

<!-- ✅ PAGINATION (1 2 3 LIKE BOOKING) -->
<div class="pagination">
<?php for($i=1;$i<=$total_pages;$i++){ ?>
    <a href="?page=<?php echo $i; ?>" 
       class="<?php echo ($i==$page)?'active':''; ?>">
        <?php echo $i; ?>
    </a>
<?php } ?>
</div>

</div>

</body>
</html>