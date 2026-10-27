<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

/* ================= THEME ================= */
:root{
    --bg:#020617;
    --card:rgba(17,24,39,0.7);
    --text:#e5e7eb;
    --accent:#38bdf8;
    --glass:blur(12px);
}
.light-mode{
    --bg:#f1f5f9;
    --card:rgba(255,255,255,0.7);
    --text:#111;
    --accent:#2563eb;
}

/* ================= BODY ================= */
body{
    background:linear-gradient(135deg,#020617,#0f172a);
    color:var(--text);
    font-family:Segoe UI;
    transition:0.3s;
}

/* ================= SIDEBAR ================= */
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    background:var(--card);
    backdrop-filter:var(--glass);
    padding:20px;
    box-shadow:5px 0 20px rgba(0,0,0,0.5);
    transition:0.3s;
}
.sidebar h4{
    text-align:center;
    color:var(--accent);
}
.sidebar a{
    display:block;
    padding:12px;
    border-radius:10px;
    color:var(--text);
    margin-bottom:8px;
    transition:0.3s;
}
.sidebar a:hover,.active{
    background:rgba(56,189,248,0.2);
    color:var(--accent);
    transform:translateX(5px);
}

/* ================= COLORFUL ICONS ================= */
.sidebar a i{
    margin-right:10px;
}

/* Individual colors */
.sidebar a:nth-child(2) i{ color:#38bdf8; }   /* Dashboard */
.sidebar a:nth-child(3) i{ color:#22c55e; }   /* Users */
.sidebar a:nth-child(4) i{ color:#f59e0b; }   /* Properties */
.sidebar a:nth-child(5) i{ color:#a855f7; }   /* Requests */
.sidebar a:nth-child(6) i{ color:#ef4444; }   /* Payments */
.sidebar a:nth-child(7) i{ color:#e11d48; }   /* Logout */

/* ================= MOBILE ================= */
.menu-btn{
    display:none;
    position:fixed;
    top:15px;
    left:15px;
    z-index:1000;
    background:var(--accent);
    color:#fff;
    border:none;
    padding:10px;
    border-radius:8px;
}
@media(max-width:768px){
    .sidebar{left:-260px;}
    .sidebar.show{left:0;}
    .menu-btn{display:block;}
    .content{margin-left:0;}
}

/* ================= CONTENT ================= */
.content{
    margin-left:260px;
    padding:20px;
}

/* ================= CARD ================= */
.card-box{
    background:var(--card);
    backdrop-filter:var(--glass);
    border-radius:15px;
    padding:20px;
    margin-bottom:20px;
    border:1px solid rgba(255,255,255,0.1);
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

/* ================= TABLE ================= */
.table{
    border-radius:12px;
    overflow:hidden;
    border:1px solid rgba(255,255,255,0.1);
}

.table th{
    background:rgba(75,85,99,0.6) !important;
    color:#e5e7eb;
    backdrop-filter:blur(10px);
}

.table td{
    background:rgba(31,41,55,0.6) !important;
    color:#f9fafb;
}

.table tr:nth-child(even) td{
    background:rgba(17,24,39,0.6) !important;
}

.table tr:hover td{
    background:rgba(75,85,99,0.8) !important;
    transition:0.3s;
}

/* ================= INPUT (SEARCH GRAY) ================= */
input, #searchBox{
    background:#374151; /* gray */
    border:1px solid #9ca3af;
    color:#fff;
    border-radius:8px;
    padding:8px;
}
input::placeholder, #searchBox::placeholder{
    color:#d1d5db;
}
input:focus{
    border-color:var(--accent);
    box-shadow:0 0 10px var(--accent);
}

/* ================= BUTTON ================= */
.btn-edit{
    background:linear-gradient(45deg,#3b82f6,#2563eb);
    border:none;
}
.btn-delete{
    background:linear-gradient(45deg,#ef4444,#dc2626);
    border:none;
}
.btn{
    border-radius:8px;
    transition:0.3s;
}
.btn:hover{
    transform:scale(1.05);
}

/* ================= LOADER ================= */
.loader{
    display:none;
    text-align:center;
}
.loader div{
    width:30px;height:30px;
    border:4px solid #ccc;
    border-top:4px solid var(--accent);
    border-radius:50%;
    animation:spin 1s linear infinite;
    margin:auto;
}
@keyframes spin{
    100%{transform:rotate(360deg);}
}

/* ================= TOGGLE ================= */
.toggle-btn{
    position:fixed;
    right:20px;
    top:15px;
    background:var(--accent);
    color:#fff;
    border:none;
    padding:10px;
    border-radius:8px;
}

</style>
</head>

<body>

<button class="menu-btn" onclick="toggleMenu()">☰</button>
<button class="toggle-btn" onclick="toggleTheme()">🌙</button>

<div class="sidebar" id="sidebar">
<h4>🏠 Admin</h4>

<a href="?page=dashboard" class="<?php if($page=='dashboard') echo 'active'; ?>"><i class="fa fa-chart-line"></i> Dashboard</a>
<a href="?page=users"><i class="fa fa-users"></i> Users</a>
<a href="?page=properties"><i class="fa fa-home"></i> Properties</a>
<a href="?page=requests"><i class="fa fa-envelope"></i> Requests</a>
<a href="?page=payments"><i class="fa fa-credit-card"></i> Payments</a>
<a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">

<?php
if($page=='dashboard') include "dashboard.php";
if($page=='users') include "users.php";
if($page=='properties') include "properties.php";
if($page=='requests') include "requests.php";
if($page=='payments') include "payments.php";
?>

</div>

<script>

/* SIDEBAR */
function toggleMenu(){
    document.getElementById("sidebar").classList.toggle("show");
}

/* THEME SAVE */
if(localStorage.getItem("theme")=="light"){
    document.body.classList.add("light-mode");
}

function toggleTheme(){
    document.body.classList.toggle("light-mode");
    localStorage.setItem("theme",
        document.body.classList.contains("light-mode") ? "light" : "dark"
    );
}

</script>

</body>
</html>