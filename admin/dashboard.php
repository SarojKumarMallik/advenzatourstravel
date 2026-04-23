<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get stats
$posts_count = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$published_count = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$draft_count = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn();
$categories_count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Auto-create page_seo table if not exists, then fetch pages
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS page_seo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_name VARCHAR(100) UNIQUE NOT NULL,
        page_title VARCHAR(255),
        meta_title VARCHAR(255),
        meta_description TEXT,
        meta_keywords TEXT,
        canonical_tag VARCHAR(255),
        analytics_code TEXT,
        views INT DEFAULT 0,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    // Add views col if missing
    $cols = $pdo->query("SHOW COLUMNS FROM page_seo LIKE 'views'")->rowCount();
    if ($cols == 0) $pdo->exec("ALTER TABLE page_seo ADD COLUMN views INT DEFAULT 0");
} catch (Exception $e) {}

$pages_count = $pdo->query("SELECT COUNT(*) FROM page_seo")->fetchColumn();
$pages_with_seo = $pdo->query("SELECT COUNT(*) FROM page_seo WHERE meta_title IS NOT NULL AND meta_title != ''")->fetchColumn();
$total_page_views = $pdo->query("SELECT COALESCE(SUM(views),0) FROM page_seo")->fetchColumn();

// Get recent posts
$recent_posts = $pdo->query("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();

// Get recent activity stats
$today_posts = $pdo->query("SELECT COUNT(*) FROM posts WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$week_posts = $pdo->query("SELECT COUNT(*) FROM posts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();

// Get top 4 pages for dashboard preview
$all_pages = $pdo->query("SELECT * FROM page_seo ORDER BY views DESC LIMIT 4")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Travel Bee Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
        #sidebar { transition: transform 0.3s ease; }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); position: fixed; z-index: 50; top: 0; left: 0; height: 100vh; }
            #sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen overflow-x-hidden">

<!-- Sidebar Overlay (mobile) -->
<div id="sidebar-overlay" onclick="closeSidebar()" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-72 bg-gray-950 text-white h-screen sticky top-0 flex flex-col p-6 shrink-0 overflow-y-auto">
    <div class="flex items-center space-x-3 mb-12">
        <!-- <img src="../img/logo.webp" alt="IQH" class="w-10 h-10 rounded-lg object-contain"> -->
        <span class="text-xl font-display font-bold">Advenzatours Travel</span>
    </div>
    
    <nav class="flex-1 space-y-2">
        <a href="dashboard.php" class="flex items-center space-x-3 text-white px-6 py-3 rounded-xl font-bold shadow-lg" style="background-color:#1171B9;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>
        <a href="manage-posts.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Manage Posts</span>
        </a>
        <a href="manage-categories.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Categories</span>
        </a>
        <a href="pages.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span>Pages</span>
        </a>
        <a href="page-seo.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>Page SEO</span>
        </a>
    </nav>
    
    <a href="logout.php" class="bg-red-500/10 text-red-500 px-6 py-3 rounded-xl font-bold border border-red-500/20 hover:bg-red-500 hover:text-white transition-all text-center">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        Logout
    </a>
</aside>    <!-- Content -->
<main class="flex-1 overflow-y-auto min-h-screen w-full">
    <header class="bg-white border-b border-gray-100 sticky top-0 z-30 px-4 md:px-8 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <!-- Hamburger -->
            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div>
                <h1 class="text-lg md:text-3xl font-display font-extrabold text-gray-900">Dashboard Overview</h1>
                <p class="text-gray-500 mt-0.5 text-xs md:text-sm hidden md:block">Welcome back, <?php echo $_SESSION['user_name']; ?>! Here's what's happening.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="../index.php" target="_blank" class="text-gray-600 hover:text-gray-900 font-semibold text-sm hidden md:flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>View Site</span>
            </a>
            <div class="w-9 h-9 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-full flex items-center justify-center font-bold text-base shadow-lg">
                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
            </div>
        </div>
    </header>

    <!-- Quick Stats -->
    <div class="p-4 md:p-8">
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Total Pages -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl text-white shadow-lg shadow-orange-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <h4 class="text-4xl font-display font-extrabold mb-1"><?php echo $pages_count; ?></h4>
            <p class="text-orange-100 text-sm font-medium">Total Pages</p>
        </div>

        <!-- Total Posts -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl text-white shadow-lg shadow-blue-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">+<?php echo $week_posts; ?> this week</span>
            </div>
            <h4 class="text-4xl font-display font-extrabold mb-1"><?php echo $posts_count; ?></h4>
            <p class="text-blue-100 text-sm font-medium">Total Posts</p>
        </div>

        <!-- Published Posts -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl text-white shadow-lg shadow-green-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full"><?php echo $draft_count; ?> drafts</span>
            </div>
            <h4 class="text-4xl font-display font-extrabold mb-1"><?php echo $published_count; ?></h4>
            <p class="text-green-100 text-sm font-medium">Published</p>
        </div>

        <!-- Categories -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl text-white shadow-lg shadow-purple-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>
            <h4 class="text-4xl font-display font-extrabold mb-1"><?php echo $categories_count; ?></h4>
            <p class="text-purple-100 text-sm font-medium">Categories</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="pages.php" class="bg-white p-6 rounded-xl border-2 border-gray-200 hover:border-orange-500 transition-all group">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-500 group-hover:text-white transition-all">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="font-bold text-lg mb-1">Manage Pages</h3>
            <p class="text-gray-500 text-sm">View SEO & analytics for all pages</p>
        </a>

        <a href="edit-post.php" class="bg-white p-6 rounded-xl border-2 border-gray-200 hover:border-blue-500 transition-all group">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h3 class="font-bold text-lg mb-1">Create New Post</h3>
            <p class="text-gray-500 text-sm">Write and publish a new blog post</p>
        </a>

        <a href="manage-categories.php" class="bg-white p-6 rounded-xl border-2 border-gray-200 hover:border-purple-500 transition-all group">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <h3 class="font-bold text-lg mb-1">Manage Categories</h3>
            <p class="text-gray-500 text-sm">Organize your blog content</p>
        </a>
    </div>

    <!-- Pages Overview -->
    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <div>
                <h3 class="font-bold text-lg">Pages Overview</h3>
                <p class="text-xs text-gray-400 font-medium mt-0.5"><?php echo $pages_with_seo; ?> of <?php echo $pages_count; ?> pages have SEO configured &bull; <?php echo number_format($total_page_views); ?> total views</p>
            </div>
            <a href="pages.php" class="text-orange-500 font-semibold text-sm hover:text-orange-600 flex items-center space-x-1">
                <span>View All</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Page Name</th>
                        <th class="px-6 py-4">Meta Title</th>
                        <th class="px-6 py-4">Meta Description</th>
                        <th class="px-6 py-4">Views</th>
                        <th class="px-6 py-4">SEO Status</th>
                        <th class="px-6 py-4">Last Updated</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($all_pages)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <p class="font-semibold">No pages found.</p>
                            <a href="page-seo.php" class="text-orange-500 hover:underline text-sm">Set up Page SEO</a>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($all_pages as $pg): ?>
                        <?php
                            $has_title  = !empty($pg['meta_title']);
                            $has_desc   = !empty($pg['meta_description']);
                            $seo_score  = ($has_title ? 1 : 0) + ($has_desc ? 1 : 0) + (!empty($pg['canonical_tag']) ? 1 : 0);
                            $seo_label  = $seo_score == 3 ? ['Good', 'bg-green-100 text-green-700'] : ($seo_score >= 1 ? ['Partial', 'bg-yellow-100 text-yellow-700'] : ['Missing', 'bg-red-100 text-red-600']);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($pg['page_name']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-[180px]">
                                <span class="truncate block" title="<?php echo htmlspecialchars($pg['meta_title'] ?? ''); ?>">
                                    <?php echo $has_title ? htmlspecialchars(substr($pg['meta_title'], 0, 40)) . (strlen($pg['meta_title']) > 40 ? '…' : '') : '<span class="text-gray-300 italic">Not set</span>'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px]">
                                <span class="truncate block" title="<?php echo htmlspecialchars($pg['meta_description'] ?? ''); ?>">
                                    <?php echo $has_desc ? htmlspecialchars(substr($pg['meta_description'], 0, 50)) . (strlen($pg['meta_description']) > 50 ? '…' : '') : '<span class="text-gray-300 italic">Not set</span>'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span class="text-sm font-semibold text-gray-700"><?php echo number_format($pg['views'] ?? 0); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full <?php echo $seo_label[1]; ?>">
                                    <?php echo $seo_label[0]; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                <?php echo $pg['updated_at'] ? date('d M Y', strtotime($pg['updated_at'])) : '-'; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="page-seo.php?page=<?php echo urlencode($pg['page_name']); ?>" class="text-orange-500 hover:text-orange-700 font-bold text-sm">Edit SEO</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Recent Posts -->
    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <h3 class="font-bold text-lg">Recent Posts</h3>
            <a href="manage-posts.php" class="text-orange-500 font-semibold text-sm hover:text-orange-600 flex items-center space-x-1">
                <span>View All</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Views</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($recent_posts)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p class="text-lg font-semibold mb-2">No posts yet</p>
                            <a href="edit-post.php" class="text-orange-500 font-semibold hover:underline">Create your first post</a>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($recent_posts as $post): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900"><?php echo htmlspecialchars($post['title']); ?></td>
                            <td class="px-6 py-4 text-gray-600 text-sm"><?php echo $post['category_name'] ?? 'Uncategorized'; ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center text-gray-500 text-sm font-mono">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <?php echo number_format($post['views'] ?? 0); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $post['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'; ?>">
                                    <?php echo ucfirst($post['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="../post.php?slug=<?php echo $post['slug']; ?>" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">View</a>
                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="text-orange-600 hover:text-orange-700 font-semibold text-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    </div><!-- end padding wrapper -->
</main>

</body>
<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebar-overlay');
    s.classList.toggle('open');
    o.classList.toggle('hidden');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}
</script>
</html>
