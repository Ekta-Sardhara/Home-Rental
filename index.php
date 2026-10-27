<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home Rental System</title>

<meta http-equiv="refresh" content="4;url=home.php">

<style>
body {
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    background: radial-gradient(circle at center, #0a1a3a, #020617);
    color:white;
    font-family: 'Segoe UI', sans-serif;
    overflow:hidden;
}

/* GLOW BACK LIGHT */
.glow {
    position:absolute;
    width:300px;
    height:300px;
    background: radial-gradient(circle, rgba(56,189,248,0.4), transparent);
    filter: blur(60px);
    animation: pulse 3s infinite;
}

/* HOUSE ICON */
.house {
    position:relative;
    width:120px;
    height:100px;
    margin-bottom:20px;
}

/* ROOF */
.roof {
    width:0;
    height:0;
    border-left:60px solid transparent;
    border-right:60px solid transparent;
    border-bottom:60px solid #38bdf8;
}

/* BASE */
.base {
    width:100px;
    height:60px;
    background:#38bdf8;
    margin:auto;
    border-radius:10px;
    margin-top:-5px;
    box-shadow:0 0 20px #38bdf8;
}

/* WINDOWS */
.window {
    width:12px;
    height:12px;
    background:#facc15;
    position:absolute;
    top:50px;
    left:30px;
    box-shadow:0 0 10px #facc15;
}
.window2 {
    left:70px;
}

/* TEXT */
.title {
    font-size:32px;
    font-weight:bold;
    margin-top:15px;
    text-shadow:0 0 15px #38bdf8;
    animation: fadeUp 2s ease;
}

.subtitle {
    color:#94a3b8;
    margin-top:5px;
    animation: fadeUp 2s ease 1s forwards;
    opacity:0;
}

/* LOADER */
.loader {
    width:200px;
    height:5px;
    background:#1e293b;
    margin-top:25px;
    border-radius:10px;
    overflow:hidden;
}
.loader span {
    display:block;
    height:100%;
    width:0%;
    background:#38bdf8;
    animation: load 4s linear forwards;
}

/* ANIMATION */
@keyframes load {
    to { width:100%; }
}
@keyframes fadeUp {
    from {opacity:0; transform:translateY(20px);}
    to {opacity:1; transform:translateY(0);}
}
@keyframes pulse {
    0%,100%{transform:scale(1);}
    50%{transform:scale(1.2);}
}
</style>
</head>

<body>

<div class="glow"></div>

<div class="house">
    <div class="roof"></div>
    <div class="base"></div>
    <div class="window"></div>
    <div class="window window2"></div>
</div>

<div class="title">Home Rental System</div>
<div class="subtitle">Find Your Perfect Stay</div>

<div class="loader"><span></span></div>

</body>
</html>