<?php
session_start();

/* 🔒 SECURITY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    header("Location: login.php");
    exit();
}

/* DATABASE */
$conn = new mysqli("localhost","root","","home_rental_system");

/* ✅ FIXED HERE */
if (!isset($_GET['property_id'])) {
    header("Location: tenant.php");
    exit();
}

$property_id = $_GET['property_id'];

/* FETCH PROPERTY */
$p = $conn->query("SELECT * FROM properties WHERE id='$property_id'")->fetch_assoc();

/* FETCH USER EMAIL (AUTO) */
$user = $conn->query("SELECT email FROM users WHERE id='{$_SESSION['user_id']}'")->fetch_assoc();
$email = $user['email'];

/* HANDLE FORM */
if(isset($_POST['send_request'])){

    $tenant_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];

    $full_name = $first_name . " " . $last_name;
    $conn->query("UPDATE users SET name='$full_name', phone='$phone' WHERE id='$tenant_id'");

    $check = $conn->query("SELECT * FROM requests WHERE tenant_id='$tenant_id' AND property_id='$property_id'");

    if($check->num_rows == 0){

        $conn->query("INSERT INTO requests (tenant_id, property_id, status) 
                      VALUES ('$tenant_id', '$property_id', 'pending')");

        echo "<script>alert('Request Sent Successfully'); window.location='tenant.php';</script>";
        exit();

    } else {
        echo "<script>alert('You already sent request!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Request Property</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI;}

body{
    background:linear-gradient(135deg,#0f172a,#020617);
    color:#fff;
}

/* ✅ CARD GRAY */
.container{
    width:400px;
    margin:80px auto;
    background:#374151;
    padding:25px;
    border-radius:15px;
    animation:fadeIn 0.5s ease;
}

/* INPUT */
input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:8px;
}

/* BUTTON */
.btn{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:#3b82f6;
    color:#fff;
    cursor:pointer;
    margin-top:10px;
}

/* BACK */
.back{
    display:block;
    text-align:center;
    margin-top:10px;
    color:#ccc;
    text-decoration:none;
}

/* TEXT */
h3{margin-bottom:10px;}
p{margin:5px 0;}

/* ANIMATION */
@keyframes fadeIn{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:translateY(0);}
}
</style>

</head>

<body>

<div class="container">

<h3>Request Property</h3>

<p><b><?php echo $p['title']; ?></b></p>
<p><?php echo $p['location']; ?></p>
<p>₹<?php echo $p['price']; ?></p>

<form method="POST">

<input type="text" name="first_name" placeholder="First Name" required>
<input type="text" name="last_name" placeholder="Last Name" required>
<input type="text" name="phone" placeholder="Phone Number" required>

<!-- hidden email (auto send) -->
<input type="hidden" name="email" value="<?php echo $email; ?>">

<button class="btn" name="send_request">Send Request</button>

</form>

<a href="tenant.php" class="back">⬅ Back</a>

</div>

</body>
</html>