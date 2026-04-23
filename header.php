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

    
