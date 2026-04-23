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
    <p class="mt-1" style="color: #F4A621;">
        <i class="fa fa-map-marker-alt mr-2"></i>
        <a href="https://share.google/XEw0MIOWUYqolAqZv" target="_blank">
            Prachivihar, Palasuni, Bhubaneswar – 751025
        </a>
    </p>

    <!-- Phone -->
    <p class="mt-2" style="color: #F4A621;">
        <i class="fa fa-phone-alt mr-2"></i>
        
<a href="tel:+918328806162">+91 8328806162</a>
  /
  <a href="tel:+917609934471">+91 7609934471</a>
    </p>

    <!-- Email -->
    <p class="mt-2" style="color: #F4A621;">
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