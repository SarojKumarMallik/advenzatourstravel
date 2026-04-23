<?php
require_once 'config/db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: blog.php');
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.status = 'published'");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if ($post) {
    try {
        // Try to increment views
        $stmt = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
        $stmt->execute([$post['id']]);
    } catch (Exception $e) {
        // If it fails, assume column might be missing and try to fix it
        try {
            $pdo->exec("ALTER TABLE posts ADD COLUMN views INT DEFAULT 0");
            // Retry update after fixing schema
            $stmt = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
            $stmt->execute([$post['id']]);
        } catch (Exception $ex) {
            // Still failed? Log it or ignore
        }
    }
}

if (!$post) {
    header('Location: blog.php');
    exit;
}

// Fetch recent posts with categories
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id != ? 
    AND p.status = 'published' 
    ORDER BY p.created_at DESC 
    LIMIT 3
");

$stmt->execute([$post['id']]);
$recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fallback if no data
if (empty($recent_posts)) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM posts p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'published' 
        ORDER BY p.created_at DESC 
        LIMIT 3
    ");
    $stmt->execute();
    $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// SEO Meta Data (Will be used if needed, though seo_tags.php is included)
$page_title = !empty($post['meta_title']) ? $post['meta_title'] : $post['title'] . ' - IQH';
$meta_description = !empty($post['meta_description']) ? $post['meta_description'] : substr(strip_tags($post['excerpt'] ?: $post['content']), 0, 160);
$meta_keywords = !empty($post['meta_keywords']) ? $post['meta_keywords'] : '';
?>

<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
  
    <base href="/">
    <?php
    // Set variables for seo_tags.php to consume if it's set up that way
    // (In this case, seo_tags.php is included but post.php handles its own meta usually)
    include 'includes/seo_tags.php';
    ?>
    
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Quicksand:wght@400;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet" />

    
    <link href="img/logo.ico" rel="icon">

    


    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <style>
      .phone {
    transform: rotate(90deg);
}
        /* Premium Interactive Typography */
        ::selection {
            background: #1171b9;
            color: white;
        }

        .prose {
            color: #374151;
        }

        /* Drop Cap Effect for the first paragraph */
        /* .prose>p:first-of-type::first-letter {
            float: left;
            font-size: 4rem;
            line-height: 1;
            font-weight: 800;
            margin-right: 0.75rem;
            color: #1171b9;
            font-family: 'Inter', sans-serif;
        } */

        .prose h1 {
            font-size: 2.75rem;
            font-weight: 800;
            color: #111827;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .prose h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            margin-top: 2.5rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
            border-left: 4px solid #1171b9;
            padding-left: 1rem;
        }

        .prose h3 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111827;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }

        .prose p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: #4b5563;
            transition: color 0.3s ease;
        }

        .prose p:hover {
            color: #111827;
        }

        /* Interactive Lists */
        .prose ul {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 1.5rem;
        }

        .prose ul li {
            position: relative;
            padding-left: 1.75rem;
            margin-bottom: 0.5rem;
            transition: transform 0.2s ease;
        }

        .prose ul li:hover {
            transform: translateX(5px);
        }

        .prose ul li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: #1171b9;
            font-weight: bold;
        }

        .prose ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
            color: #4b5563;
        }

        .prose ol li {
            margin-bottom: 0.5rem;
            padding-left: 0.5rem;
        }

        .prose strong {
            color: #111827;
            font-weight: 700;
        }

        /* Elegant Blockquote */
        .prose blockquote {
            position: relative;
            background: #eff6ff;
            border-left: 6px solid #1171b9;
            padding: 2rem;
            font-style: italic;
            color: #4b5563;
            margin: 2.5rem 0;
            font-size: 1.4rem;
            border-radius: 0 1.5rem 1.5rem 0;
            box-shadow: 0 10px 15px -3px rgba(17, 113, 185, 0.1);
        }

        /* Interactive Images */
        .prose img {
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            margin: 3rem 0;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: zoom-in;
        }

        .prose img:hover {
            transform: scale(1.02) translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(17, 113, 185, 0.2);
        }

        /* Animated Links */
        .prose a {
            color: #1171b9;
            text-decoration: none;
            font-weight: 700;
            position: relative;
            padding: 0 2px;
        }

        .prose a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #1171b9;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }

        .prose a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* MOBILE FIX */
        @media (max-width: 768px) {

            /* Reset all forced margins/paddings */
            .footer-wrapper .footer-top .col-sm-6,
            .footer-wrapper .widget-area .col-md-6,
            .footer-wrapper .copyright .col-auto {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            /* Logo center */
            .footer1-logo {
                text-align: center !important;
            }

            .footer1-logo img {
                margin: 0 auto !important;
                display: block;
            }

            /* Remove squeeze/negative margin */
            .footer-wrapper .widget-area .col-md-6 {
                max-width: 100% !important;
            }

            /* Visiting hours fix */
            .footer-table td {
                display: block;
                width: 100%;
                text-align: left !important;
            }

            /* Address wrap */
            .address-line a {
                max-width: 100% !important;
                display: inline-block;
            }

            /* Copyright center */
            .copyright .row {
                flex-direction: column;
                text-align: center !important;
            }

            /* Privacy Terms center on mobile */
            .footer-bottom-menu {
                justify-content: center !important;
                display: flex;
                gap: 15px;
                margin-top: 10px;
            }

            .header-main .col-auto {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Fix logo position */
            .header1-logo img {
                width: 160px !important;
                margin: 0 auto !important;
                display: block;
            }

            /* Make header row proper flex */
            .header-main .row {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            /* Fix call section */
            .header-call {
                margin: 0 !important;
            }

            /* Reduce call text size */
            .header-call .h4 {
                font-size: 14px !important;
            }

            .header-call span {
                font-size: 10px !important;
            }

        }

        /* BLOG PAGE HEADER FIX (992px–1366px) */
        @media (min-width: 992px) and (max-width: 1366px) {

            /* Remove forced margins from inline styles */
            .header-main .col-auto,
            .header-top .col-auto {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Fix logo alignment */
            .header1-logo img {
                max-width: 230px;
                margin-left: 0 !important;
            }

            /* Proper row alignment */
            .header-main .row {
                flex-wrap: nowrap;
                align-items: center;
            }

            /* Center menu */
            .header-main .col.text-lg-center {
                flex: 1;
                display: flex;
                justify-content: center;
            }

            /* Menu spacing */
            .main-menu ul li {
                margin: 0 10px;
            }

            /* Phone fix */
            .header-call {
                margin-bottom: 0 !important;
                margin-left: 10px;
                white-space: nowrap;
            }

            /* Align phone text */
            .header-call .media-body {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            /* Font adjust */
            .header-call .h4 {
                font-size: 16px;
            }

        }

        .footer-widget h4 a {
            font-size: 22px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            word-break: normal;
            overflow-wrap: anywhere;
        }

        @media (max-width: 1366px) {
            .footer-widget h4 a {
                font-size: 17px;
                /* slightly reduced */
            }
        }

        @media (max-width: 991px) {
            .footer-widget h4 a {
                font-size: 16px;
            }
        }

        @media (min-width: 1400px) {
            .footer-widget h4 a {
                font-size: 20px;
            }
        }
    </style>
</head>

<body class="">
     <style>

        .fa-phone-alt{
            transform: rotate(90deg);
        }

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

    


    <!-- Immersive Hero -->
    <section class="relative h-[65vh] min-h-[500px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">

        
            <?php
            $featured_img = $post['featured_image'];

            if (!empty($featured_img)) {
                if (strpos($featured_img, 'http') === 0) {
                    // External image
                    $featured_img = $featured_img;
                } else {
                    // Local image (FIX PATH)
                    $featured_img = '/Traveler/' . ltrim($featured_img, '/');
                }
            }
            ?>
            <img src="<?php echo $featured_img; ?>"  class="w-full h-full object-cover"
                alt="<?php echo htmlspecialchars($post['image_alt'] ?: $post['title']); ?>">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center text-white">
            <div class="animate-fade-in space-y-6">
                <span
                    class="inline-block bg-[#1171b9] text-white px-6 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg">
                    <?php echo $post['category_name']; ?>
                </span>
                <h1
                    class="text-4xl md:text-5xl font-extrabold leading-tight max-w-4xl mx-auto drop-shadow-2xl text-white">
                    <?php echo $post['title']; ?>
                </h1>
                <div
                    class="flex flex-wrap items-center justify-center gap-8 text-sm md:text-base font-semibold opacity-90">
                    <span class="flex items-center"><span class="mr-2">📅</span>
                        <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                    <span class="flex items-center"><span class="mr-2">👁️</span>
                        <?php echo number_format($post['views'] ?? 0); ?> Views</span>
                    <span class="flex items-center"><span class="mr-2">⏱️</span> 6 min read</span>
                    <span class="flex items-center"><span class="mr-2">👤</span> Admin</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Layout -->
    <div class="container mx-auto px-4 -mt-24 relative z-20 pb-24">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Main Content -->
            <main class="flex-1 max-w-4xl">
                <article class="bg-white rounded-[2.5rem] p-8 md:p-16 shadow-2xl shadow-black/5 border border-gray-100">
                    <!-- Breadcrumbs -->
                    <nav
                        class="flex items-center space-x-2 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-12">
                        <a href="index.php" class="hover:text-[#1171b9]">Home</a>
                        <span>/</span>
                        <a href="blog.php" class="hover:text-[#1171b9]">Blog</a>
                        <span>/</span>
                        <span class="text-gray-900 truncate max-w-[200px]"><?php echo $post['title']; ?></span>
                    </nav>

                    <div
                        class="prose prose-lg md:prose-xl max-w-none prose-p:text-gray-600 prose-p:leading-relaxed prose-img:rounded-3xl prose-img:shadow-xl">
                        <?php echo $post['content']; ?>
                    </div>

                    <!-- Footer Share -->
                    <div
                        class="mt-20 pt-10 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-8">

                        <a href="blog.php"
                            class="bg-gray-100 text-gray-600 px-8 py-3 rounded-xl font-bold text-sm hover:bg-[#1171b9] hover:text-white transition-all">←
                            Back to Blog</a>
                    </div>
                </article>
            </main>

            <!-- Sidebar -->
            <aside class="w-full lg:w-96 space-y-12 shrink-0">
                <!-- Newsletter -->
                <div
                    class="bg-[#1171b9] rounded-[2.5rem] p-10 text-white relative overflow-hidden group shadow-2xl shadow-blue-100">

                    <div
                        class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-700">
                    </div>

                    <h3 class="text-2xl font-bold mb-4 relative z-10 text-white">Need Help?</h3>

                    <p class="text-white/80 text-sm mb-8 relative z-10 leading-relaxed font-medium">
                        Have questions about your condition or treatment? Our experts are here to help you. Get in touch
                        with us today.
                    </p>

                    <!-- Button instead of form -->
                    <a href="contact.php#contact-form"
                        class="block w-full text-center bg-white text-[#1171b9] font-bold py-3 rounded-xl hover:shadow-xl transition-all active:scale-95 text-xs uppercase tracking-wider">
                        Contact
                    </a>

                </div>

                <!-- Recent Posts -->
                <div
                    class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl shadow-black/5 sticky top-24">
                    <h3 class="text-xl font-extrabold mb-8 flex items-center">
                        <span class="w-1.5 h-6 bg-[#1171b9] rounded-full mr-3"></span>
                        Recent Articles
                    </h3>
                    <div class="space-y-4">
                        
                        <?php foreach ($recent_posts as $rp): ?>
                            <a href="blog/<?php echo $rp['slug']; ?>"
                                class="group flex gap-4 items-center bg-gray-50 hover:bg-white p-3 rounded-2xl transition-all duration-300 hover:shadow-lg border border-transparent hover:border-blue-100 transform hover:-translate-y-1">
                                <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 shadow-sm relative">
                                    <?php
                                    $rp_img = $rp['featured_image'];

                                    if (!empty($rp_img)) {
                                        if (strpos($rp_img, 'http') === 0) {
                                            $rp_img = $rp_img;
                                        } else {
                                            $rp_img = '/Traveler/' . ltrim($rp_img, '/');
                                        }
                                    }
                                    ?>
                                    <img src="<?php echo $rp_img; ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        alt="">
                                </div>
                                <div class="flex flex-col items-start px-1">
                                    <span
                                        class="bg-blue-50 text-[#1171b9] text-[9px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full mb-1.5">
                                        <?php echo $rp['category_name'] ?? 'Healthcare'; ?>
                                    </span>
                                    <h4
                                        class="font-bold text-sm text-gray-900 group-hover:text-[#1171b9] line-clamp-2 leading-snug mb-1 transition-colors">
                                        <?php echo $rp['title']; ?>
                                    </h4>
                                    <p class="text-[10px] text-gray-400 font-medium flex items-center mt-auto">
                                        <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <?php echo date('M d, Y', strtotime($rp['created_at'])); ?>
                                    </p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        // Reading Progress
        window.onscroll = function () {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            if (scrolled > 100) scrolled = 100;
            document.getElementById("progress-bar").style.width = scrolled + "%";
        };
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
    <p style="color: #F4A621;" class="mt-2" >
        <i class="fa fa-map-marker-alt mr-2"></i>
        <a href="https://share.google/XEw0MIOWUYqolAqZv" target="_blank" >
            Prachivihar, Palasuni, Bhubaneswar – 751025
        </a>
    </p>

    <!-- Phone -->
    <p style="color: #F4A621;" class="mt-2">
        <i class="fa fa-phone-alt mr-2"></i>
        
<a href="tel:+918328806162">+91 8328806162</a>
  /
  <a href="tel:+917609934471">+91 7609934471</a>
    </p>

    <!-- Email -->
    <p style="color: #F4A621;" class="mt-2">
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

    <!--==============================
        All Js File
    ============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Slick Slider -->
    <script src="assets/js/slick.min.js"></script>
    <!-- <script src="assets/js/app.min.js"></script> -->
    <!-- Layerslider -->
    <script src="assets/js/layerslider.utils.js"></script>
    <script src="assets/js/layerslider.transitions.js"></script>
    <script src="assets/js/layerslider.kreaturamedia.jquery.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- jQuery Datepicker -->
    <script src="assets/js/jquery.datetimepicker.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Isotope Filter -->
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <!-- Parallax Scroll -->
    <script src="assets/js/universal-parallax.min.js"></script>
    <!-- WOW Animation -->
    <script src="assets/js/wow.min.js"></script>
    <!-- Custom Carousel -->
    <script src="assets/js/vscustom-carousel.min.js"></script>
    <!-- Form Js -->
    <script src="assets/js/ajax-mail.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let currentPath = window.location.pathname;

            document.querySelectorAll(".main-menu ul li a").forEach(function (link) {
                let href = link.getAttribute("href");

                // Activate Blog for both blog.php AND blog/slug
                if (
                    currentPath.includes("blog.php") ||
                    currentPath.includes("/blog/")
                ) {
                    if (href === "blog.php") {
                        link.parentElement.classList.add("active");
                    }
                } else {
                    if (currentPath.includes(href.replace(".php", ""))) {
                        link.parentElement.classList.add("active");
                    }
                }
            });

        });
    </script>
</body>

</html>