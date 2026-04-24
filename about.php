<?php require_once 'config/db.php';
require_once 'includes/track_view.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/seo_tags.php'; ?>

    <!-- Favicon -->
     <link href="img/logo.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
   <style>

/* ================= ROOT ================= */
:root {
  --accent: #F4A621;
  --black: #000;
  --dark: #0a0a0a;
  --white: #fff;
  --gray: #9ca3af;
}

a{
    text-decoration: none !important;
}

/* ================= TOPBAR ================= */
.topbar-sec{
  background:#F4A621;
  color:#fff;
  padding:8px 0;
  font-size:13px;
  border-bottom:1px solid rgba(255,255,255,0.05);
}

.topbar-wrap{
  max-width:1400px;
  margin:auto;
  padding:0 20px;
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
}

.topbar-left{
  display:flex;
  gap:20px;
}

.topbar-item{
  display:flex;
  gap:6px;
  align-items:center;
}

.topbar-right{
  display:flex;
  gap:10px;
}

.topbar-right a{
  width:32px;
  height:32px;
  border-radius:50%;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:0.3s;
  color: #F4A621;
}

.topbar-right a:hover{
  background:var(--accent);
  color:#000;
  transform:translateY(-3px);
}

/* ================= NAVBAR ================= */
.main-nav-sec{
  position:sticky;
  top:0;
  background:rgba(0,0,0,0.85);
  backdrop-filter:blur(12px);
  border-bottom:1px solid rgba(255,255,255,0.08);
  z-index:999;
}

.nav-wrap{
  max-width:1400px;
  margin:auto;
  padding:0 20px;
  height:85px;
  display:flex;
  align-items:center;
  justify-content:space-between;
}

/* LOGO */
.nav-logo img{
  height:110px;
  width:150px;
  object-fit:contain;
}

/* MENU */
.nav-menu{
  display:flex;
  gap:18px;
}

.nav-item{
  font-weight:500;
  color:#ddd;
  position:relative;
  padding:6px 10px;
  transition:0.3s;
}

.nav-item:hover{
  color:var(--accent);
}

.nav-item::after{
  content:"";
  position:absolute;
  bottom:-4px;
  left:0;
  width:0%;
  height:2px;
  background:var(--accent);
  transition:0.3s;
}

.nav-item:hover::after{
  width:100%;
}

/* ================= BUTTON ================= */
.nav-right{
  display:flex;
  align-items:center;
  gap:15px;
}

.btn-book{
  background:linear-gradient(135deg,#F4A621,#F4A621);
  color:#fff;
  padding:10px 20px;
  border-radius:30px;
  font-size:14px;
  font-weight:600;
  transition:0.3s;
}
.btn-book:hover{
    color: white !important;
    cursor: pointer;
}

/* ================= MOBILE ================= */
.menu-btn{
  display:none;
  font-size:24px;
  color:#fff;
  cursor:pointer;
}

/* Overlay */
.mob-overlay{
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.6);
  opacity:0;
  visibility:hidden;
  transition:0.3s;
  z-index:1500;
}

.mob-overlay.active{
  opacity:1;
  visibility:visible;
}

/* Mobile Menu */
.mob-menu{
  position:fixed;
  top:0;
  right:-100%;
  width:280px;
  height:100%;
  background:#0a0a0a;
  transition:0.4s;
  padding:30px 20px;
  z-index:2000;
  display:flex;
  flex-direction:column;
  gap:10px;
}

.mob-menu.active{
  right:0;
}

.mob-item{
  padding:14px 10px;
  border-radius:8px;
  color:#ccc;
  font-size:15px;
  transition:0.3s;
}

.mob-item:hover{
  background:rgba(255,255,255,0.05);
  color:var(--accent);
  padding-left:15px;
}

/* ================= RESPONSIVE ================= */
@media(max-width:992px){

  /* 🔴 HIDE TOPBAR */
  .topbar-sec{
    display:none;
  }

  .nav-menu{display:none;}
  .btn-book{display:none;}
  .menu-btn{display:block;}

  .nav-wrap{
    height:70px;
  }

  .nav-logo img{
    height:80px;
    width:120px;
  }

}

</style>


<!-- TOPBAR -->
<div class="topbar-sec">
  <div class="topbar-wrap">
    <div class="topbar-left">
      <div class="topbar-item">
  <i class="fa fa-phone-alt"></i> 
  
<a href="tel:+918328806162">+91 8328806162</a>
  /
  <a href="tel:+917609934471">+91 7609934471</a>
</div>

<div class="topbar-item">
  <i class="fa fa-envelope"></i> 
  <a href="mailto:advenzatoours99@gmail.com">advenzatoours99@gmail.com</a>
</div>
    </div>

    <div class="topbar-right">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="https://www.youtube.com/@AdvenzaToursandTravel"><i class="fab fa-youtube"></i></a>
      <a href="https://www.instagram.com/advenza_tours?igsh=MWVsM2g1NnEzeTYyeg=="><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</div>


<!-- NAVBAR -->
<header class="main-nav-sec">
  <div class="nav-wrap">

    <div class="nav-logo">
      <img src="img/logo.webp">
    </div>

    <nav class="nav-menu">
      <a href="index.php" class="nav-item">Home</a>
      <a href="about.php" class="nav-item">About</a>
       <a href="service.php" class="nav-item">Service</a>
      <a href="package.php" class="nav-item">Package</a>
     
      <a href="gallery.php" class="nav-item">Gallery</a>
      <a href="blog.php" class="nav-item">Blog</a>
      <a href="contact.php" class="nav-item">Contact</a>
    </nav>

    <div class="nav-right">
      <a class="btn-book" href="tel:7609934471">Book Taxi</a>
      <div class="menu-btn" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
      </div>
    </div>

  </div>
</header>


<!-- OVERLAY -->
<div class="mob-overlay" id="overlay" onclick="toggleMenu()"></div>

<!-- MOBILE MENU -->
<div class="mob-menu" id="mobileMenu">
  <a href="index.php" class="mob-item">Home</a>
  <a href="about.php" class="mob-item">About</a>
  <a href="service.php" class="mob-item">Service</a>
  <a href="package.php" class="mob-item">Package</a>
  
  <a href="gallery.php" class="mob-item">Gallery</a>
  <a href="blog.php" class="mob-item">Blog</a>
  <a href="contact.php" class="mob-item">Contact</a>
</div>


<script>
function toggleMenu(){
  document.getElementById("mobileMenu").classList.toggle("active");
  document.getElementById("overlay").classList.toggle("active");
}
</script>


 <!-- FLOATING CONTACT START -->
<div class="floating-container">
  <div class="floating-stack">

    <!-- Toggle Button -->
    <div class="floating-toggle-btn" onclick="toggleContact()">
      <i id="toggleIcon" class="fas fa-chevron-right"></i>
    </div>

    <!-- Contact Buttons -->
    <div id="contactWrapper" class="floating-contact-wrapper open">

      <!-- WhatsApp -->
      <a 
  href="https://wa.me/7609934471?text=Hello! I would like to book a taxi / tour package. Please share details about pricing, availability, and destinations." 
  target="_blank"
  class="floating-btn whatsapp">
  <i class="fab fa-whatsapp"></i>
</a>

      <!-- Call -->
      <a href="tel:8328806162" class="floating-btn call">
        <i class="fas fa-phone-alt"></i>
      </a>

    </div>

  </div>
</div>
<!-- FLOATING CONTACT END -->

<style>
  /* ================= CONTAINER ================= */

.floating-container {
  position: fixed;
  right: 0;
  top: 55%;
  transform: translateY(-50%);
  z-index: 999;
}

/* Vertical Stack */
.floating-stack {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

/* ================= TOGGLE BUTTON ================= */

.floating-toggle-btn {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #111827, #1f2937);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 8px 0 0 8px;
  font-size: 20px;
  transition: 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
  margin-bottom: 8px;
}

/* ================= CONTACT WRAPPER ================= */

.floating-contact-wrapper {
  display: flex;
  flex-direction: column;
  transition: all 0.4s ease;
  overflow: hidden;
}

.floating-contact-wrapper.open {
  width: 50px;
  opacity: 1;
}

.floating-contact-wrapper.closed {
  width: 0;
  opacity: 0;
}

/* ================= BUTTONS ================= */

.floating-btn {
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: #fff;
  text-decoration: none;
  transition: 0.3s ease;
}

.floating-btn.whatsapp {
  background: #25d366;
}

.floating-btn.call {
  background: #1d4ed8;
  border-radius: 0 0 0 18px;
}

/* ================= MOBILE ================= */

@media (max-width: 768px) {

  .floating-btn {
    width: 55px;
    height: 55px;
    font-size: 22px;
  }

  .floating-toggle-btn {
    width: 36px;
    height: 36px;
    font-size: 18px;
  }
}
</style>

<script>
let open = true;

function toggleContact() {
  const wrapper = document.getElementById("contactWrapper");
  const icon = document.getElementById("toggleIcon");

  open = !open;

  if (open) {
    wrapper.classList.add("open");
    wrapper.classList.remove("closed");
    icon.classList.remove("fa-chevron-left");
    icon.classList.add("fa-chevron-right");
  } else {
    wrapper.classList.add("closed");
    wrapper.classList.remove("open");
    icon.classList.remove("fa-chevron-right");
    icon.classList.add("fa-chevron-left");
  }
}
</script>



    <!-- Header Start -->
    <div class="container-fluid page-header">
        <div class="container">
            <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
                <h3 class="display-4 text-white text-uppercase">About</h3>
                <div class="d-inline-flex text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="">Home</a></p>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase">About</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    


     <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container pt-5">
            <div class="row">
                <div class="col-lg-6" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="img/about.webp" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 pt-5 pb-lg-5">
                    <div class="about-text bg-white p-4 p-lg-5 my-lg-5">
                        <h6 class="text-uppercase" style="letter-spacing: 5px; color: #F4A621;">About Us</h6>
                        <h1 class="mb-3">Best Tours and Travel Agency in Bhubaneswar</h1>
                        <p>Advenza Tours and Travel is a trusted tours and travel agency in Bhubaneswar, Odisha, dedicated to making travel easy, comfortable, and well-organized. Recognized as one of the best tours and travel agencies in Bhubaneswar, we help travelers plan meaningful journeys with reliable services and local expertise.</p>
                        <p>Based in Bhubaneswar, we serve clients from Cuttack, Puri, Khordha, and nearby areas of Odisha, offering personalized travel solutions for both leisure and business needs.</p>
                        <div class="row mb-4">
                            <div class="col-6">
                                <img class="img-fluid" src="img/gallery/koraput1.webp" alt="">
                            </div>
                            <div class="col-6">
                                <img class="img-fluid" src="img/about-2.webp" alt="">
                            </div>
                        </div>
                        <a href="tel:+918328806162" class="btn mt-1" style="background-color: #F4A621; color: #fff;">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Feature Start -->
    <div class="container-fluid pb-2">
        <div class="container pb-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center mr-3" style="height: 100px; width: 100px; background-color: #F4A621;">
                            <i class="fa fa-2x fa-money-check-alt text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">Competitive Pricing</h5>
                            <p class="m-0">Affordable pricing across Odisha with quality service and best value</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center mr-3" style="height: 100px; width: 100px; background-color: #F4A621;">
                            <i class="fa fa-2x fa-award text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">Best Services</h5>
                            <p class="m-0">Professional support with guided tours, comfort stays, and smooth travel</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center mr-3" style="height: 100px; width: 100px; background-color: #F4A621;">
                            <i class="fa fa-2x fa-globe text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">Explore Entire Odisha</h5>
                            <p class="m-0">Explore all Odisha destinations from beaches temples hills and cities</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature End -->

    <style>


.dest-sec{
    background:#f0f2f3;
    padding:50px 20px 50px;
    text-align:center;
}

.dest-sub{
    color:#F4A621;
    letter-spacing:6px;
    font-size:13px;
    font-weight:600;
    margin-bottom:10px;
}

.dest-title{
    font-size:30px;
    font-weight:700;
    color:#1a1a1a;
    margin:0;
}


/* RESPONSIVE */
@media(max-width:768px){
    .dest-title{
        font-size:28px;
    }
    .dest-sub{
        letter-spacing:4px;
        font-size:12px;
    }
}

</style>



<style>

/* ===== HEADING ===== */
.dest-sec{
    background:#f0f2f3;
    padding:50px 20px 50px;
    text-align:center;
}

.dest-sub{
    color:#F4A621;
    letter-spacing:6px;
    font-size:13px;
    font-weight:600;
    margin-bottom:10px;
}

.dest-title{
    font-size:30px;
    font-weight:700;
    color:#1a1a1a;
    margin:0;
}

/* ===== SECTION ===== */
.car-sec{
    background:#f0f2f3;
    padding:0px 20px 40px;
}

/* GRID */
.car-grid{
    max-width:1200px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:30px;
}

/* CARD */
.car-card{
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition:0.4s;
}

.car-card:hover{
    transform:translateY(-10px) scale(1.02);
    box-shadow:0 25px 60px rgba(0,0,0,0.15);
}

/* IMAGE */
.car-img{
    position:relative;
    overflow:hidden;
}

.car-img img{
    width:100%;
    height:260px;
    object-fit:cover;
}

/* OVERLAY */
.car-img::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(to top,rgba(0,0,0,0.4),transparent);
}

/* CONTENT */
.car-content{
    padding:22px;
}

.car-title{
    font-weight:700;
    font-size:18px;
    margin-bottom:5px;
}

.car-price{
    color:#F4A621;
    font-weight:700;
    margin-bottom:15px;
}

.car-info{
    font-size:14px;
    color:#555;
    display:grid;
    gap:10px;
    margin-bottom:20px;
}

.car-info div{
    display:flex;
    align-items:center;
    gap:8px;
}

/* BUTTON */
.car-btn{
    display:block;
    text-align:center;
    background:linear-gradient(135deg,#F4A621,#ffb347);
    color:#000;
    padding:12px;
    border-radius:30px;
    font-weight:600;
    text-decoration:none;
    transition:0.3s;
}

.car-btn:hover{
    background:#000;
    color:#fff;
}

/* ===== TEXT SECTION ===== */
.car-desc{
    max-width:1200px;
    margin:50px auto 0;
    text-align:left;
    color:#555;
    line-height:1.8;
}

.car-desc p{
    margin-bottom:20px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .dest-title{
        font-size:28px;
    }
    .dest-sub{
        letter-spacing:4px;
        font-size:12px;
    }
}

</style>


<!-- HEADING -->
<section class="dest-sec">
    <div class="dest-sub">DRIVE WITH COMFORT</div>
    <h2 class="dest-title">Rent Cars In Bhubaneswar</h2>
</section>


<!-- CAR SECTION -->
<section class="car-sec">

    <div class="car-grid">

        <!-- EXISTING 3 -->

        <!-- INNOVA -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/innova.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">INNOVA</div>
                <div class="car-price">Starting ₹ 16/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: MPV</div>
                    <div>👥 4 Doors, 7-8 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
                <a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

        <!-- CRYSTA -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/crysta.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">CRYSTA</div>
                <div class="car-price">Starting ₹ 18/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: MPV</div>
                    <div>👥 4 Doors, 7 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

        <!-- HONDA CITY -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/honda.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">Honda City</div>
                <div class="car-price">Starting ₹ 16/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: Sedan</div>
                    <div>👥 4 Doors, 5 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

        <!-- NEW 3 -->

        <!-- VERNA -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/verna.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">VERNA</div>
                <div class="car-price">Starting ₹ 16/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: Sedan</div>
                    <div>👥 4 Doors, 5 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

        <!-- BOLERO -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/bolero.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">BOLERO</div>
                <div class="car-price">Starting ₹ 16/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: SUV</div>
                    <div>👥 5 Doors, 7 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

        <!-- ERTIGA -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/ertiga.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">Ertiga</div>
                <div class="car-price">Starting ₹ 15/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: MPV</div>
                    <div>👥 5 Doors, 7 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

        <!-- ERTIGA -->
        <div class="car-card">
            <div class="car-img">
                <img src="img/dzire.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">DZIRE</div>
                <div class="car-price">Starting ₹ 12/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: Sedan</div>
                    <div>👥 4 Doors, 5 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>


        <div class="car-card">
            <div class="car-img">
                <img src="img/amaze.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">AMAZE</div>
                <div class="car-price">Starting ₹ 12/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: Sedan</div>
                    <div>👥 4 Doors, 5 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>
        <div class="car-card">
            <div class="car-img">
                <img src="img/tabera.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">TABERA</div>
                <div class="car-price">Starting ₹ 16/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: MPV</div>
                    <div>👥 4 Doors, 7-9 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>  
        <div class="car-card">
            <div class="car-img">
                <img src="img/creata.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">CRETA</div>
                <div class="car-price">Starting ₹ 14/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: SUV</div>
                    <div>👥 5 Doors, 5 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>
        <div class="car-card">
            <div class="car-img">
                <img src="img/traveller.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">Traveller</div>
                <div class="car-price">Starting ₹ 24/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: LCV</div>
                    <div>👥 13 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>
        <div class="car-card">
            <div class="car-img">
                <img src="img/traveller-17.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">Traveller</div>
                <div class="car-price">Starting ₹ 27/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: MPV</div>
                    <div>👥 17 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>
        <div class="car-card">
            <div class="car-img">
                <img src="img/traveller-17.webp" alt="">
            </div>
            <div class="car-content">
                <div class="car-title">Traveller</div>
                <div class="car-price">Starting ₹ 30/km</div>
                <div class="car-info">
                    <div>🚗 Vehicle Type: MPV</div>
                    <div>👥 26 Seats</div>
                    <div>⚙️ Transmission: Automatic/Manual</div>
                </div>
                
<a href="tel:7609934471" class="car-btn">RESERVE NOW</a>
            </div>
        </div>

    </div>

    <!-- NEW TEXT SECTION -->
    <div class="car-desc">

        <p>
        As a trusted travel agency in Bhubaneswar, Advenza Tours and Travel offers reliable and comfortable car rental services for local travel, airport transfers, sightseeing, and outstation journeys. Our vehicles are well-maintained and driven by experienced professionals to ensure safe and smooth travel across Bhubaneswar and nearby destinations.
        </p>

        <p>
        Whether you need a car for business travel, family outings, or holiday tours, we provide flexible rental options with transparent pricing. With our strong local knowledge and customer-focused service, Advenza Tours and Travel makes transportation easy, convenient, and stress-free for every traveler.
        </p>

    </div>

</section>


<!-- HEADING -->
<section class="dest-sec">
    <div class="dest-sub">YOUR QUESTIONS ANSWERED</div>
    <h2 class="dest-title">Frequently Asked Questions</h2>
</section>

<!-- FAQ SECTION -->
<section class="sr-faq-sec">
    <div class="sr-faq-container">

        <div class="sr-faq-item active">
            <div class="sr-faq-q">
                <span>What documents are required to rent a car in Bhubaneswar?</span>
                <div class="sr-faq-icon"></div>
            </div>
            <div class="sr-faq-a">
                You need a valid driving license and a government-issued ID such as Aadhaar or PAN card for verification.
            </div>
        </div>

        <div class="sr-faq-item">
            <div class="sr-faq-q">
                <span>Do you provide cars for outstation travel?</span>
                <div class="sr-faq-icon"></div>
            </div>
            <div class="sr-faq-a">
                Yes, we provide both local and outstation car rental services with flexible packages across Odisha.
            </div>
        </div>

        <div class="sr-faq-item">
            <div class="sr-faq-q">
                <span>Is fuel included in the rental price?</span>
                <div class="sr-faq-icon"></div>
            </div>
            <div class="sr-faq-a">
                Fuel is not included. The vehicle will be given with a certain fuel level and should be returned the same.
            </div>
        </div>

        <div class="sr-faq-item">
            <div class="sr-faq-q">
                <span>Do you offer driver services?</span>
                <div class="sr-faq-icon"></div>
            </div>
            <div class="sr-faq-a">
                Yes, professional drivers are available on request for a comfortable and stress-free journey.
            </div>
        </div>

        <div class="sr-faq-item">
            <div class="sr-faq-q">
                <span>What is your cancellation policy?</span>
                <div class="sr-faq-icon"></div>
            </div>
            <div class="sr-faq-a">
                Free cancellation is available up to 24 hours before the trip. Late cancellations may include charges.
            </div>
        </div>

    </div>
</section>

<style>

/* ===== FAQ SECTION ===== */
.sr-faq-sec{
    background:#f0f2f3;
    padding:0px 20px 80px;
}

.sr-faq-container{
    max-width:1100px;
    margin:auto;
}

/* FAQ ITEM */
.sr-faq-item{
    background:#fff;
    border-radius:12px;
    margin-bottom:15px;
    overflow:hidden;
    border:1px solid #e5e7eb;
    transition:0.3s ease;
}

/* ACTIVE STYLE */
.sr-faq-item.active{
    border-color:#F4A621;
    box-shadow:0 8px 25px rgba(0,0,0,0.06);
}

/* QUESTION */
.sr-faq-q{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 20px;
    cursor:pointer;
    font-weight:600;
    color:#1a1a1a;
    font-size:16px;
}

/* ICON (PLUS → MINUS) */
.sr-faq-icon{
    width:18px;
    height:18px;
    position:relative;
}

.sr-faq-icon::before,
.sr-faq-icon::after{
    content:'';
    position:absolute;
    background:#F4A621;
    transition:0.3s;
}

.sr-faq-icon::before{
    width:18px;
    height:2px;
    top:8px;
    left:0;
}

.sr-faq-icon::after{
    width:2px;
    height:18px;
    left:8px;
    top:0;
}

/* ACTIVE ICON (MINUS) */
.sr-faq-item.active .sr-faq-icon::after{
    opacity:0;
}

/* ANSWER */
.sr-faq-a{
    max-height:0;
    overflow:hidden;
    padding:0 20px;
    font-size:14px;
    color:#555;
    line-height:1.6;
    transition:0.4s ease;
}

/* ACTIVE ANSWER */
.sr-faq-item.active .sr-faq-a{
    max-height:200px;
    padding:0 20px 20px;
}

/* HOVER EFFECT */
.sr-faq-item:hover{
    transform:translateY(-2px);
}

/* RESPONSIVE */
@media(max-width:768px){
    .sr-faq-q{
        font-size:15px;
    }
}

</style>

<script>

/* FAQ TOGGLE */
const srFaq = document.querySelectorAll(".sr-faq-item");

srFaq.forEach(item => {
    item.addEventListener("click", () => {

        srFaq.forEach(el => el.classList.remove("active"));

        item.classList.toggle("active");

    });
});

</script>


<!-- HEADING -->
<section class="dest-sec">
    <div class="dest-sub">TOP TRAVEL PACKAGES</div>
    <h2 class="dest-title">Customer’s Reviews</h2>
</section>

<!-- ================= TESTIMONIAL SECTION ================= -->
<section class="zx-testimonial-sec">

    

    <div class="zx-slider">
        <div class="zx-track" id="zxTrack">

            <!-- ORIGINAL CARDS -->
            <div class="zx-card">
                <div class="zx-top">
                    <img src="https://lh3.googleusercontent.com/a-/ALV-UjXepwFBCr3xLLKsqM0ZS8r0_REsVpAoCjxir8tyMN6nAE-fSZgL=w72-h72-p-rp-mo-br100" class="zx-img">
                    <div>
                        <div class="zx-name">Dr. Baikuntha Narayan Dash</div>

                        <div class="zx-stars">
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star-o"></i>
</div>
                        
                    </div>
                </div>
                <p class="zx-text">Our Daringdadi trip from 1.2.26 to 3.2.26 was excellent. Advenza tour and travel tour operator managed the trip nicely. Two Innova crysta vehicles were hired by us from the tour operator, which were very comfortable. The drivers were very cooperative. Enjoyed the trip</p>
            </div>

            <div class="zx-card">
                <div class="zx-top">
                    <img src="https://lh3.googleusercontent.com/a/ACg8ocIeUR96tsDoA7QmY1PMmgIGpNud7-9AoaRwxh7fdlc63y204GE=w72-h72-p-rp-mo-br100" class="zx-img">
                    <div>
                        <div class="zx-name">Rani Sahoo</div>
                                                <div class="zx-stars">
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
</div>
                       
                    </div>
                </div>
                <p class="zx-text">Wanted a quick weekend getaway and my friend suggested this agency. Totally worth it! They planned everything -hotel, sightseeing. The hotel was super comfy and the itinerary was spot on. Would definitely book again! 😊</p>
            </div>

            <div class="zx-card">
                <div class="zx-top">
                    <img src="https://lh3.googleusercontent.com/a/ACg8ocJPfyqV49O10M_-6jy5A2UvUI7lmEp6vw9ggF3tJIblnUMB4Q0=w72-h72-p-rp-mo-br100" class="zx-img">
                    <div>
                        <div class="zx-name">Padmalochan</div>
                                                <div class="zx-stars">
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
</div>
                       
                    </div>
                </div>
                <p class="zx-text">They are provided good service , they are having trusted and professional drivers .overally my experience is good for advenza tour and travel</p>
            </div>

            <div class="zx-card">
                <div class="zx-top">
                    <img src="https://lh3.googleusercontent.com/a-/ALV-UjUyvclr9DlQ0OrAlAxaOR3ikNUHPyYaJ4EBUxlINFCqWDRSFXRQ=w72-h72-p-rp-mo-br100" class="zx-img">
                    <div>
                        <div class="zx-name">Tapas Das</div>
                                                <div class="zx-stars">
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
    <i class="fa fa-star"></i>
</div>
                        
                    </div>
                </div>
                <p class="zx-text">Advenza tour's is a good travel agency but good behaviour and priority.....</p>
            </div>

        </div>
    </div>
    <div class="zx-container mt-4">
        
        <a href="https://www.google.com/search?q=advenzatourstravel&rlz=1C1RXQR_enIN1141IN1141&oq=advenzatourstravel&gs_lcrp=EgZjaHJvbWUqBggAEEUYOzIGCAAQRRg7MgYIARBFGDwyCggCEAAYCBgNGB4yDQgDEAAYCBgNGB4YyQMyBggEEEUYPDIGCAUQRRg8MgYIBhBFGDwyBggHEEUYPNIBBzczMmowajeoAgCwAgA&sourceid=chrome&ie=UTF-8" class="zx-btn">View All</a>
    </div>

</section>

<!-- ================= CSS ================= -->
<style>
    /* ===== STARS ===== */
.zx-stars {
    margin-top: 4px;
}

.zx-stars i {
    color: #F4A621;
    font-size: 13px;
    margin-right: 2px;
}

.zx-testimonial-sec{
    padding:20px 20px;
    background:#f7f7f7;
    overflow:hidden;
}

.zx-container{
    text-align:center;
    margin-bottom:40px;
}

.zx-title{
    font-size:32px;
    font-weight:700;
    color:#222;
    margin-bottom:15px;
}

/* BUTTON */
.zx-btn{
    display:inline-block;
    padding:10px 22px;
    background:#F4A621;
    color:#fff;
    border-radius:30px;
    font-size:14px;
    text-decoration:none;
    transition:0.3s;
}

.zx-btn:hover{
    background:#d98e10;
}

.zx-slider{
    overflow:hidden;
    max-width:1200px;
    margin:auto;
}

.zx-track{
    display:flex;
    gap:20px;
    transition:transform 0.6s ease;
}

.zx-card{
    background:#fff;
    border-radius:18px;
    padding:25px;
    min-width:calc(33.33% - 14px);
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.zx-top{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:15px;
}

.zx-img{
    width:50px;
    height:50px;
    border-radius:50%;
}

.zx-name{
    font-weight:600;
}

.zx-role{
    font-size:13px;
    color:#888;
}

.zx-text{
    font-size:14px;
    color:#555;
    line-height:1.6;
}

/* RESPONSIVE */
@media(max-width:992px){
    .zx-card{ min-width:calc(50% - 10px); }
}

@media(max-width:600px){
    .zx-card{ min-width:100%; }
}

</style>

<!-- ================= JS ================= -->
<script>

const track = document.getElementById("zxTrack");
let index = 0;
let autoSlide;

/* Clone for seamless loop */
function cloneCards() {
    const cards = document.querySelectorAll(".zx-card");
    cards.forEach(card => {
        const clone = card.cloneNode(true);
        track.appendChild(clone);
    });
}

cloneCards();

function getVisibleCards() {
    if (window.innerWidth <= 600) return 1;
    if (window.innerWidth <= 992) return 2;
    return 3;
}

function slide() {
    const cards = document.querySelectorAll(".zx-card");
    const visible = getVisibleCards();

    index++;

    const cardWidth = cards[0].offsetWidth + 20;
    track.style.transform = `translateX(-${index * cardWidth}px)`;

    /* Seamless reset (NO JUMP) */
    if (index >= cards.length / 2) {
        setTimeout(() => {
            track.style.transition = "none";
            index = 0;
            track.style.transform = `translateX(0px)`;

            setTimeout(() => {
                track.style.transition = "transform 0.6s ease";
            }, 50);
        }, 600);
    }
}

function startAuto() {
    autoSlide = setInterval(slide, 2500);
}

startAuto();

/* Reset on resize */
window.addEventListener("resize", () => {
    index = 0;
    track.style.transform = `translateX(0px)`;
});

</script>


    <!-- SWIPER CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <style>

/* ===== HEADING ===== */
.dest-sec{
    background:#f4f4f4;
    padding:40px 20px 30px;
    text-align:center;
}

.dest-sub{
    color:#F4A621;
    letter-spacing:6px;
    font-size:13px;
    font-weight:600;
}

.dest-title{
    font-size:38px;
    font-weight:700;
    color:#1a1a1a;
}

/* ===== SLIDER ===== */
.banner-sec{
    background:#f4f4f4;
    padding-bottom:60px;
}

.banner-slider{
    max-width:1200px;
    margin:auto;
    border-radius:20px;
    overflow:hidden;
}

/* SLIDE */
.banner-slide{
    position:relative;
    height:420px;
}

/* IMAGE */
.banner-slide img{
    width:100%;
    height:100%;
    
}

/* OVERLAY */
.banner-overlay{
    position:absolute;
    inset:0;
    background:linear-gradient(to right,rgba(0,0,0,0.6),rgba(0,0,0,0.2));
    display:flex;
    align-items:center;
    padding:50px;
}

/* TEXT */
.banner-text{
    color:#fff;
    max-width:500px;
}

.banner-text h2{
    font-size:34px;
    font-weight:600;
    margin-bottom:20px;
    line-height:1.3;
}

.banner-text p{
    letter-spacing:3px;
    font-size:14px;
    opacity:0.9;
}

/* DOTS */
.swiper-pagination-bullet{
    background:#ccc;
    opacity:1;
}

.swiper-pagination-bullet-active{
    background:#F4A621;
}

/* RESPONSIVE */
@media(max-width:768px){
    .banner-slide{
        height:280px;
    }
    .banner-text h2{
        font-size:20px;
    }
    .banner-overlay{
        padding:20px;
    }
}

</style>


<!-- HEADING -->
<section class="dest-sec">
    <div class="dest-sub">TOP TRAVEL PACKAGES</div>
    <h2 class="dest-title">Most Popular Package</h2>
</section>


<!-- SLIDER -->
<section class="banner-sec">

    <div class="swiper banner-slider">
        <div class="swiper-wrapper">

            <!-- SLIDE 1 -->
            <div class="swiper-slide banner-slide">
                <img src="img/kashmir.webp" alt="">
               
            </div>
             <!-- SLIDE 3 -->
            <div class="swiper-slide banner-slide">
                <img src="img/family.webp" alt="">
                
            </div>

            <!-- SLIDE 2 -->
            <div class="swiper-slide banner-slide">
                <img src="img/ladakh.webp" alt="">
                
            </div>

            <!-- SLIDE 3 -->
            <div class="swiper-slide banner-slide">
                <img src="img/sp.webp" alt="">
                
            </div>
           
            <!-- SLIDE 3 -->
            <div class="swiper-slide banner-slide">
                <img src="img/delhi.webp" alt="">
                
            </div>

        </div>

        <!-- DOTS -->
        <div class="swiper-pagination"></div>
    </div>

</section>


<!-- Destination End --><!-- SWIPER JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
new Swiper(".banner-slider", {
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    }
});
</script>



   


   <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 py-5 px-sm-3 px-lg-5" style="margin-top: 90px;">
        <div class="row pt-5">
            <div class="col-lg-3 col-md-6 mb-5">
                <a href="" class="navbar-brand">
                    <img src="img/logo.webp" alt="" style=" width: 200px;">
                </a>
                <p>Advenza Tours & Travels offers reliable cab services and customized tour packages for comfortable, affordable journeys.</p>
                <h6 class="text-white text-uppercase mt-4 mb-3" style="letter-spacing: 5px;">Follow Us</h6>
                <div class="d-flex justify-content-start">
    
    

     <a class="btn btn-square mr-2" href="#"
       style="border:1px solid #F4A621; color:#F4A621;">
       <i class="fab fa-facebook-f"></i>
    </a>
   
    <a class="btn btn-square mr-2" href="https://www.youtube.com/@AdvenzaToursandTravel" 
   style="border:1px solid #F4A621; color:#F4A621;">
   <i class="fab fa-youtube"></i>
</a>

   
    <a class="btn btn-square mr-2" href="https://www.instagram.com/advenza_tours?igsh=MWVsM2g1NnEzeTYyeg=="
       style="border:1px solid #F4A621; color:#F4A621;">
       <i class="fab fa-instagram"></i>
    </a>

    
  

</div>
            </div>
            <div class="col-lg-2 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Our Services</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white-50 mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Home</a>
                    <a class="text-white-50 mb-2" href="about.php"><i class="fa fa-angle-right mr-2"></i>About</a>
                    
                    <a class="text-white-50 mb-2" href="service.php"><i class="fa fa-angle-right mr-2"></i>Services</a>
                    <a class="text-white-50 mb-2" href="gallery.php"><i class="fa fa-angle-right mr-2"></i>Gallery</a>
                    <a class="text-white-50 mb-2" href="package.php"><i class="fa fa-angle-right mr-2"></i>Packages</a>
                    
                    <a class="text-white-50" href="blog.php"><i class="fa fa-angle-right mr-2"></i>Blog</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Package</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white-50 mb-2" href="puri-konark-tour-package.php"><i class="fa fa-angle-right mr-2"></i>Puri-Konark Tour Package</a>
                    <a class="text-white-50 mb-2" href="koraput-tour-package.php"><i class="fa fa-angle-right mr-2"></i>Koraput Tour Package</a>
                    <a class="text-white-50 mb-2" href="sambalpur-bolangir-tour-package.php"><i class="fa fa-angle-right mr-2"></i>Sambalpur-Bolangir Tour Package</a>
                    <a class="text-white-50 mb-2" href="daringibadi-tour-package.php"><i class="fa fa-angle-right mr-2"></i>Daringbadi Tour Package</a>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
    <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Contact Us</h5>


     <!-- MAP -->
   <div class="map-box mb-1">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3123.216204812192!2d85.86819867428513!3d20.29974761241981!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a190b0052e7e17f%3A0x49a28d7b4e807d45!2sADVENZA%20TOUR&#39;S%20AND%20TRAVEL!5e1!3m2!1sen!2sin!4v1776492370905!5m2!1sen!2sin"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>
    

    <!-- Address -->
    <p>
        <i class="fa fa-map-marker-alt mr-2"></i>
        <a href="https://share.google/XEw0MIOWUYqolAqZv" target="_blank">
            Prachivihar, Palasuni, Bhubaneswar – 751025
        </a>
    </p>

    <!-- Phone -->
    <p>
        <i class="fa fa-phone-alt mr-2"></i>
        
<a href="tel:+918328806162">+91 8328806162</a>
  /
  <a href="tel:+917609934471">+91 7609934471</a>
    </p>

    <!-- Email -->
    <p>
        <i class="fa fa-envelope mr-2"></i>
        <a href="mailto:advenzatoours99@gmail.com">advenzatoours99@gmail.com</a>
    </p>

   
</div>

<style>
.map-box{
    border-radius:12px;
    overflow:hidden;
    margin-top:10px;
}
.map-box iframe{
    width:100%;
    height:180px;
    border:0;
}
</style>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
        <div class="row">
            <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
                <p class="m-0 text-white-50">Copyright &copy; <a href="index.php" style="color: #F4A621;">Advenza Tours & Travel </a>. All Rights Reserved.</a>
                </p>
            </div>
            <div class="col-lg-6 text-center text-md-right">
                <!--/*** The author’s attribution link below must remain intact on your website. ***/-->
                <!--/*** If you wish to remove this credit link, please purchase the Pro Version from https://htmlcodex.com . ***/-->
                <!-- <p class="m-0 text-white-50">Designed by <a href="https://htmlcodex.com" style="color: #F4A621;">HTML Codex</a></p> -->
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #F4A621; color: #fff;"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>