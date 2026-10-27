<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home Rental System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body {
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#020617;
    color:#e5e7eb;
}

/* NAV */
.top-nav {
    background: rgba(2,6,23,0.9);
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #1e293b;
}

.center-nav a, .right-links a {
    color:#94a3b8;
    margin:0 10px;
    text-decoration:none;
}
.center-nav a:hover, .right-links a:hover {
    color:#38bdf8;
}

/* HERO */
.hero {
    background: url('uploads/hero.jpg') no-repeat center/cover;
    min-height: 90vh;
    display:flex;
    align-items:center;
    padding:60px;
}

.hero-inner {
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:100%;
    gap:40px;
}

.hero-content {
    max-width:550px;
}

.hero h1 {
    font-size:3rem;
    color:#38bdf8;
}

.hero-image img {
    width:380px;
    border-radius:20px;
    box-shadow:0 0 40px rgba(56,189,248,0.6);
    animation: float 4s infinite;
}

@keyframes float {
    0%{transform:translateY(0);}
    50%{transform:translateY(-15px);}
    100%{transform:translateY(0);}
}

/* CARDS */
.cards-container {
    display:flex;
    justify-content:center;
    gap:30px;
    margin:40px;
    flex-wrap:wrap;
}

.card-option {
    width:260px;
    background:#0f172a;
    border-radius:15px;
    padding:20px;
    text-align:center;
    border:1px solid #1e293b;
    transition:0.3s;
}

.card-option:hover {
    transform:translateY(-10px);
    box-shadow:0 0 20px #38bdf8;
}

.icon-box {
    width:90px;
    height:90px;
    margin:auto;
    margin-bottom:15px;
    border-radius:15px;
    background:#020617;
    display:flex;
    justify-content:center;
    align-items:center;
}

.icon-box i {
    font-size:40px;
    color:#38bdf8;
}

/* ABOUT */
.about {
    padding:60px 20px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:40px;
    flex-wrap:wrap;
}

.about img {
    width:350px;
    border-radius:15px;
}

.about-text {
    max-width:500px;
}

.about-text h2 {
    color:#38bdf8;
}

/* FEATURES */
.features {
    padding:60px 20px;
    text-align:center;
}

.features h2 {
    color:#38bdf8;
    margin-bottom:40px;
}

.feature-grid {
    display:flex;
    justify-content:center;
    gap:25px;
    flex-wrap:wrap;
}

.feature-box {
    width:250px;
    padding:25px;
    background:#0f172a;
    border-radius:15px;
    border:1px solid #1e293b;
    transition:0.3s;
}

.feature-box i {
    font-size:35px;
    color:#38bdf8;
    margin-bottom:10px;
}

.feature-box:hover {
    transform:translateY(-10px);
    box-shadow:0 0 20px #38bdf8;
}

/* 🔥 SIMPLE CONTACT (NO BOXES) */
.contact-section {
    padding:60px 20px;
    text-align:center;
}

.contact-section p {
    font-size:18px;
    color:#94a3b8;
    line-height:2;
}

/* FOOTER */
.footer {
    background:#020617;
    padding:40px 20px;
    border-top:1px solid #1e293b;
}

.footer-grid {
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:30px;
}

.footer-box {
    max-width:250px;
}

.footer-box h4 {
    color:#38bdf8;
    margin-bottom:10px;
}

.footer-box a {
    display:block;
    color:#94a3b8;
    text-decoration:none;
    margin:5px 0;
}

.footer-box a:hover {
    color:#38bdf8;
}

/* SOCIAL */
.social-icons a {
    color:#94a3b8;
    margin-right:10px;
    font-size:20px;
}
.social-icons a:hover {
    color:#38bdf8;
}

/* MOBILE */
@media(max-width:900px){
    .hero-inner {
        flex-direction:column;
        text-align:center;
    }
    .hero-image img {
        width:280px;
    }
}

</style>
</head>

<body>

<!-- NAV -->
<div class="top-nav">
    <h2>Home Rental System</h2>

    <div class="center-nav">
        <a href="#">Home</a>
        <a href="#about">About</a>
        <a href="#features">Features</a>
        <a href="booking.php">Booking</a>
        <a href="#contact">Contact</a>
    </div>

    <div class="right-links">
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <h1>Welcome to Home Rental System</h1>
            <p>Discover, Book & Manage rental homes easily.</p>
        </div>

        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c">
        </div>
    </div>
</div>

<!-- CARDS -->
<div class="cards-container">
    <a href="owner.php" class="card-option">
        <div class="icon-box"><i class="fas fa-user-tie"></i></div>
        <h3>Owner</h3>
    </a>

    <a href="tenant.php" class="card-option">
        <div class="icon-box"><i class="fas fa-user"></i></div>
        <h3>Tenant</h3>
    </a>

    <a href="booking.php" class="card-option">
        <div class="icon-box"><i class="fas fa-calendar-check"></i></div>
        <h3>Booking</h3>
    </a>
</div>

<!-- ABOUT -->
<section id="about" class="about">
    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa">
    <div class="about-text">
        <h2>About Us</h2>
        <p>
            Home Rental System helps users find, book, and manage rental homes easily.
        </p>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="features">
    <h2>Our Features</h2>

    <div class="feature-grid">
        <div class="feature-box">
            <i class="fas fa-home"></i>
            <h4>Property Management</h4>
        </div>

        <div class="feature-box">
            <i class="fas fa-lock"></i>
            <h4>Secure Login</h4>
        </div>

        <div class="feature-box">
            <i class="fas fa-bolt"></i>
            <h4>Fast Booking</h4>
        </div>

        <div class="feature-box">
            <i class="fas fa-mobile-alt"></i>
            <h4>Responsive Design</h4>
        </div>
    </div>
</section>

<!-- 🔥 SIMPLE CONTACT -->
<section id="contact" class="contact-section">
    <h2 style="color:#38bdf8;">Contact Us</h2>
    <p>
        📍 Surat, Gujarat, India <br>
        📧 homerental@gmail.com <br>
        📞 +91 9876543210 <br>
        🕒 Mon - Sat: 9:00 AM - 7:00 PM
    </p>
</section>

<!-- FOOTER -->
<div class="footer">
    <div class="footer-grid">

        <div class="footer-box">
            <h4>About</h4>
            <p>Modern home rental platform for easy booking and management.</p>
        </div>

        <div class="footer-box">
            <h4>Quick Links</h4>
            <a href="#">Home</a>
            <a href="#about">About</a>
            <a href="#features">Features</a>
        </div>

        <div class="footer-box">
            <h4>Support</h4>
            <a href="#">register</a>
            <a href="#">login</a>
            <a href="#">Terms</a>
        </div>

        <div class="footer-box">
            <h4>Follow Us</h4>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>

    </div>

    <p style="text-align:center;margin-top:20px;">© 2026 Home Rental System</p>
</div>

</body>
</html>