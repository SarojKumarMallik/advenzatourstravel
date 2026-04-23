<?php require_once 'config/db.php';
require_once 'includes/track_view.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <?php include 'includes/seo_tags.php'; ?>

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--==============================
       Google Web Fonts
    ============================== -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Quicksand:wght@400;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet" />

    <link href="img/logo.ico" rel="icon">

   
    <script>
        tailwind.config = {
            corePlugins: {
                preflight: false   // 🔥 prevents breaking navbar/footer
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    

 

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
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
    <?php include 'header.php' ?>

    <?php
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';

    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'published'";
    $params = [];

    if ($search) {
        $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($category) {
        $sql .= " AND c.slug = ?";
        $params[] = $category;
    }

    $sql .= " ORDER BY p.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll();

    $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
    ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Health & Wellness Blog</h1>


            <!-- Search and Filter -->
            <div class="max-w-2xl mx-auto">
                <form method="GET"
                    class="flex flex-col md:flex-row gap-4 bg-white p-2 rounded-2xl shadow-lg border border-gray-100">
                    <input type="text" name="search" placeholder="Search medical articles..."
                        value="<?php echo htmlspecialchars($search); ?>"
                        class="flex-1 px-5 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                    <select name="category"
                        class="px-5 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 min-w-[180px]">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['slug']; ?>" <?php echo $category === $cat['slug'] ? 'selected' : ''; ?>>
                                <?php echo $cat['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit"
                        class="bg-[#1171b9] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#0e5fa0] transition-all shadow-md active:scale-95">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>


    <!-- Blog Grid -->
    <section class="container mx-auto px-4 py-16">
        <?php if (empty($posts)): ?>
            <!-- No Posts Message -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No Articles Found</h3>
                    <p class="text-gray-500 mb-6">We couldn't find any blog posts matching your criteria. Try adjusting your
                        search or category filter.</p>
                    <a href="blog.php"
                        class="inline-block bg-[#1171b9] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#0e5fa0] transition-all shadow-md">
                        Clear All Filters
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post): ?>
                    <article
                        class="group bg-white rounded-3xl overflow-hidden hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col h-full">
                        <a href="blog/<?php echo $post['slug']; ?>" class="block relative overflow-hidden aspect-video">
                            <?php
                            $featured_img = $post['featured_image'];
                            if (!empty($featured_img) && strpos($featured_img, 'http') !== 0) {
                                // Handled by backend, assuming paths are correct
                            }
                            ?>
                            <img src="<?php echo $featured_img; ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="<?php echo htmlspecialchars($post['image_alt'] ?: $post['title']); ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <span
                                class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-[#1171b9] px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                                <?php echo $post['category_name']; ?>
                            </span>
                        </a>

                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-1 bg-blue-500 rounded-full"></span>
                                <time class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                                    <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                </time>
                            </div>

                            <h3 class="mb-4">
                                <a href="blog/<?php echo $post['slug']; ?>"
                                    class="text-xl font-extrabold text-gray-900 group-hover:text-[#1171b9] transition-colors line-clamp-2 leading-tight">
                                    <?php echo $post['title']; ?>
                                </a>
                            </h3>

                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-6 flex-1">
                                <?php
                                $excerptText = isset($post['excerpt']) ? $post['excerpt'] : '';
                                $contentText = isset($post['content']) ? $post['content'] : '';
                                echo $excerptText ?: substr(strip_tags($contentText), 0, 150) . '...';
                                ?>
                            </p>

                            <a href="blog/<?php echo $post['slug']; ?>"
                                class="inline-flex items-center text-[#1171b9] font-bold text-sm tracking-wide group-hover:translate-x-1 transition-transform">
                                Read Full Article
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

     <?php include 'footer.php' ?>

    <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-bottom style2"><i class="fas fa-arrow-alt-up"></i></a>

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

            let currentPage = window.location.pathname.split("/").pop();

            document.querySelectorAll(".main-menu ul li a").forEach(function (link) {
                let linkPage = link.getAttribute("href").replace(".php", "");

                if (currentPage === linkPage) {
                    link.parentElement.classList.add("active");
                }
            });

        });
    </script>
</body>

</html>