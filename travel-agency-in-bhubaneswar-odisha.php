<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>TRAVELER - Free Travel Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

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
                <h3 class="display-4 text-white text-uppercase">Best Travel Agency in Bhubaneswar</h3>
                <div class="d-inline-flex text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="">Home</a></p>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase">Blog</p>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase">Best Travel Agency in Bhubaneswar</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


   <!-- BLOG SECTION START -->
<section class="advx-blog-sec">
    
    <div class="advx-blog-container">

        <h1 class="advx-blog-title">
            Best Travel Agency in Bhubaneswar – Advenza Tours & Travel
        </h1>

        <!-- IMAGE BELOW HEADING -->
        <div class="advx-blog-img-wrap">
            <img 
                src="img/Blog1.png" 
                alt="Bhubaneswar Travel Destination"
                class="advx-blog-img">
        </div>

        <p class="advx-blog-text">
            If you are planning a trip to Odisha, choosing the best travel agency in Bhubaneswar can make your journey smooth, comfortable, and stress-free. A well-planned trip is not just about visiting destinations, but about enjoying every moment without worrying about travel arrangements.
        </p>

        <p class="advx-blog-text">
            With Advenza Tours & Travel, you get reliable service, affordable pricing, and complete travel support. From local sightseeing to full tour packages, everything is handled professionally to give you a hassle-free experience.
        </p>

        <h2 class="advx-blog-heading">
            Why Bhubaneswar is a Popular Travel Destination
        </h2>

        <p class="advx-blog-text">
            Bhubaneswar, known as the Temple City of India, is one of the most important travel destinations in Odisha. The city offers a unique combination of heritage, spirituality, and modern lifestyle, making it suitable for all types of travelers.
        </p>

        <p class="advx-blog-text">
            From ancient temples like Lingaraj Temple and Mukteswar Temple to peaceful locations like Dhauli, Bhubaneswar attracts visitors from across the country. The rich culture and historical significance make it a must-visit destination.
        </p>

        <p class="advx-blog-text">
            In addition to its local attractions, Bhubaneswar also serves as a gateway to nearby destinations such as 
            <a href="#">Puri</a>, 
            <a href="#">Konark</a>, 
            <a href="#">Chilika Lake</a>, and many other scenic places. This makes it an ideal starting point for exploring Odisha.
        </p>

        <h2 class="advx-blog-heading">
            Importance of Choosing the Best Travel Agency in Bhubaneswar
        </h2>

        <p class="advx-blog-text">
            Selecting the best travel agency in Bhubaneswar plays a crucial role in your overall travel experience. A professional travel agency helps you plan your journey properly, manage your schedule, and avoid unnecessary complications.
        </p>

        <p class="advx-blog-text">
            Instead of handling everything yourself, a trusted travel agency takes care of transportation, bookings, and route planning. This allows you to enjoy your trip without stress and ensures a safe and well-organized journey.
        </p>

    </div>

</section>
<!-- BLOG SECTION END -->

<style>
    .advx-blog-sec {
    width: 100%;
    background: #ffffff;
    padding: 50px 15px;
}

.advx-blog-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* TITLE */
.advx-blog-title {
    text-align: left;
    color: #F4A621;
    font-size: 32px;
    margin-bottom: 20px;
}

/* IMAGE */
.advx-blog-img-wrap {
    text-align: center;
    margin-bottom: 25px;
}

.advx-blog-img {
    width: 100%;
   
    max-height: 450px;
    border-radius: 8px;
}

/* HEADINGS */
.advx-blog-heading {
    color: #F4A621;
    font-size: 22px;
    margin-top: 35px;
    margin-bottom: 10px;
}

/* TEXT */
.advx-blog-text {
    font-size: 16px;
    color: #444;
    margin-bottom: 15px;
    text-align: left;
}

/* LINKS */
.advx-blog-sec a {
    color: #F4A621;
    text-decoration: none;
    font-weight: 500;
}

.advx-blog-sec a:hover {
    text-decoration: underline;
}

/* RESPONSIVE */
@media (max-width: 768px) {

    .advx-blog-title {
        font-size: 24px;
    }

    .advx-blog-heading {
        font-size: 20px;
    }

    .advx-blog-text {
        font-size: 15px;
    }
}
</style>


<!-- SERVICES & DETAILS SECTION START -->
<section class="advx-blog-sec advx-blog-sec-extra">

    <div class="advx-blog-container">

        <h2 class="advx-blog-heading">
            Our Travel Services in Bhubaneswar
        </h2>

        <p class="advx-blog-text">
            At Advenza Tours & Travel, we provide complete travel solutions designed to meet every traveler’s needs. Our services include car rental, taxi services, and customized tour packages across Odisha.
        </p>

        <p class="advx-blog-text">
            Our car rental service in Bhubaneswar offers clean and well-maintained vehicles suitable for both local and outstation travel. Our taxi service ensures timely pickup and drop, whether you are traveling to the airport, railway station, or exploring the city.
        </p>

        <p class="advx-blog-text">
            We focus on comfort, safety, and reliability so that your journey remains smooth from start to finish.
        </p>

        <h2 class="advx-blog-heading">
            Odisha Tour Packages We Offer
        </h2>

        <p class="advx-blog-text">
            We specialize in customized tour packages that allow you to explore Odisha in the best possible way. Each package is designed to give you a comfortable and memorable travel experience.
        </p>

        <p class="advx-blog-text">
            If you are planning a spiritual and coastal journey, you can explore our 
            <a href="/puri-konark-tour-package.php">Puri Konark Tour Package</a>, 
            which covers famous temples and heritage sites.
        </p>

        <p class="advx-blog-text">
            For a peaceful hill station experience, the 
            <a href="/daringbadi-tour-package.php">Daringbadi Tour Package</a> 
            offers beautiful natural scenery and a refreshing climate.
        </p>

        <p class="advx-blog-text">
            For those interested in tribal culture and natural beauty, the 
            <a href="/koraput-tour-package.php">Koraput Tour Package</a> 
            provides a unique travel experience.
        </p>

        <p class="advx-blog-text">
            Similarly, the 
            <a href="/sambalpur-tour-package.php">Sambalpur Tour Package</a> 
            is ideal for exploring cultural heritage, temples, and river landscapes.
        </p>

        <h2 class="advx-blog-heading">
            Why Advenza Tours & Travel is the Best Travel Agency in Bhubaneswar
        </h2>

        <p class="advx-blog-text">
            Advenza Tours & Travel is trusted by many travelers for its quality service and customer-first approach. Our strong local knowledge helps you explore both popular destinations and hidden gems across Odisha.
        </p>

        <p class="advx-blog-text">
            We provide customized travel plans based on your preferences, ensuring that every journey is comfortable and enjoyable. Our pricing is transparent, with no hidden charges, which makes your travel planning easier and more reliable.
        </p>

        <p class="advx-blog-text">
            Our dedicated support team is always available to assist you, ensuring that your travel experience remains smooth and stress-free.
        </p>

        <h2 class="advx-blog-heading">
            Benefits of Choosing a Professional Travel Agency
        </h2>

        <p class="advx-blog-text">
            Traveling with a professional agency offers several advantages that improve your overall experience. It saves your time, ensures proper planning, and provides safe and reliable travel arrangements.
        </p>

        <p class="advx-blog-text">
            With organized services and expert guidance, your journey becomes more enjoyable and hassle-free. You also get access to better travel options and well-managed itineraries that enhance your trip.
        </p>

        <h2 class="advx-blog-heading">
            Explore Odisha with Comfort and Confidence
        </h2>

        <p class="advx-blog-text">
            Odisha is known for its rich culture, beautiful landscapes, temples, beaches, and hill stations. Exploring all these places becomes easier when you have a trusted travel partner by your side.
        </p>

        <p class="advx-blog-text">
            With Advenza Tours & Travel, you can explore Odisha with complete comfort and confidence. Whether you are planning a short trip or a full tour, we ensure that your journey is well-organized and memorable.
        </p>

        <h2 class="advx-blog-heading">
            Contact the Best Travel Agency in Bhubaneswar
        </h2>

        <p class="advx-blog-text">
            If you are looking for the best travel agency in Bhubaneswar, Advenza Tours & Travel is here to help you plan your perfect trip.
        </p>

        <p class="advx-blog-text">
            📞 Call / WhatsApp: <a href="tel:+918328806162">+91 8328806162</a><br><br>
            📧 Email: <a href="mailto:advenzatoours99@gmail.com">advenzatoours99@gmail.com</a>
           
        </p>

    </div>

</section>
<!-- SERVICES & DETAILS SECTION END -->

<style>
  .advx-blog-sec-extra {
    padding-top: 10px;
}
</style>



>



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

    

    
  <a class="btn btn-square" href="https://www.instagram.com/advenza_tours?igsh=MWVsM2g1NnEzeTYyeg=="
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
    <a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #F4A621; color: white;"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>