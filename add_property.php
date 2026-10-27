<?php
session_start();

/* SECURITY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

/* DB */
$conn = new mysqli("localhost","root","","home_rental_system");

/* UPLOAD FUNCTION */
function uploadImage($file){
    $target="uploads/";
    if(!is_dir($target)) mkdir($target);

    $name=time()."_".basename($file['name']);
    move_uploaded_file($file['tmp_name'],$target.$name);

    return $target.$name;
}

/* ADD PROPERTY */
if(isset($_POST['add'])){
    $img = uploadImage($_FILES['image']);

    $conn->query("INSERT INTO properties(owner_id,title,location,price,image)
    VALUES('{$_SESSION['user_id']}','{$_POST['title']}','{$_POST['location']}','{$_POST['price']}','$img')");

    // ✅ Redirect to owner page
    header("Location: owner.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Property</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
    font-family:Segoe UI;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* CARD */
.card{
    background:rgba(255,255,255,0.05);
    padding:25px;
    border-radius:15px;
    width:320px;
    backdrop-filter:blur(10px);
}

/* TITLE */
h2{
    text-align:center;
    margin-bottom:15px;
}

/* INPUT */
input{
    width:100%;
    padding:9px;
    margin-bottom:10px;
    background:transparent;
    border:1px solid #444;
    color:#fff;
    border-radius:6px;
}

/* BUTTON */
.btn{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:linear-gradient(90deg,#22c55e,#4ade80);
    cursor:pointer;
    font-weight:bold;
}

/* BACK LINK */
.back{
    display:block;
    text-align:center;
    margin-top:12px;
    color:#38bdf8;
    text-decoration:none;
}

/* HOVER */
.btn:hover{
    opacity:0.9;
}

/* MOBILE */
@media(max-width:400px){
    .card{width:90%;}
}
</style>

</head>

<body>

<div class="card">

<h2>Add Property</h2>

<form method="POST" enctype="multipart/form-data">

<input name="title" placeholder="Property Title" required>

<input name="location" placeholder="Location" required>

<input name="price" placeholder="Price" required>

<input type="file" name="image" required>

<button name="add" class="btn">Add Property</button>

</form>

<a href="owner.php" class="back">⬅ Back</a>

</div>

<!-- ✅ POPUP SCRIPT (will not show after redirect, kept as it is) -->
<script>
<?php if(isset($_GET['success'])): ?>
    alert("✅ Property Added Successfully!");
<?php endif; ?>
</script>

</body>
</html>