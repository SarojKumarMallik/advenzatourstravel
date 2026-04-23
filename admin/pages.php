<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Auto-ensure table exists + views column
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
    $cols = $pdo->query("SHOW COLUMNS FROM page_seo LIKE 'views'")->rowCount();
    if ($cols == 0) $pdo->exec("ALTER TABLE page_seo ADD COLUMN views INT DEFAULT 0");

    // Seed pages if empty
    $pages_grouped = [
        'Main Pages'     => ['Home', 'About', 'Blog', 'Contact', 'Doctors', 'services'],
        // 'Doctors'        => ['Dr. Bhushan Chittawadagi', 'Dr. Dileep M', 'Dr. Mahendra Madesh',
        //                      'Dr. Mohammed Muzamil', 'Dr. Naveen Ganjoo', 'Dr. Pruthvi Raj',
        //                      'Dr. Raksha Madhu', 'Dr. Manoj Karthik'],
        // 'General Surgery'=> ['General Surgery', 'Appendix Surgery', 'Fissure Treatment', 'Fistula Treatment',
        //                      'Gallbladder Removal', 'Hernia Surgery', 'Laser Piles Surgery',
        //                      'Piles Treatment', 'Pilonidal Sinus Treatment', 'Umbilical Hernia Treatment',
        //                      'Varicose Veins Treatment'],
        // 'Gastro & Liver' => ['Gastro & Liver', 'Chronic Intestine Inflammation', 'Hepatitis Treatment',
        //                      'IBD Treatment', 'Liver Disease Treatment'],
        // 'Neurology'      => ['Neurosurgery', 'Brain Tumor & Cranial Procedures', 'Functional Neurosurgery',
        //                      'Hydrocephalus & CSF Flow', 'Neuro-trauma & Emergency',
        //                      'Neurovascular Surgery', 'Spine Neurosurgery'],
        // 'OBG'            => ['OBG', 'Pregnancy Care', 'Uterus Removal Surgery', 'Breast Infection Treatment',
        //                      'Endometriosis Treatment', 'Fibroid Removal Surgery', 'Infertility Treatment',
        //                      'Ovarian Cyst Removal', 'Urine Leakage Treatment', 'Uterus Cleaning Procedure'],
        // 'Orthopedics'    => ['Orthopaedics', 'ACL Reconstruction Surgery', 'PCL Reconstruction Surgery',
        //                      'Hip Replacement Surgery', 'Knee Arthroscopy', 'Knee Replacement Surgery',
        //                      'Shoulder Arthroscopy', 'Shoulder Replacement Surgery', 'Slip Disc Surgery', 'Spine Surgery'],
        // 'Urology'        => ['Urology', 'Kidney Stone Removal', 'Prostate Surgery', 'Ureteric Stone Removal',
        //                      'Circumcision Surgery', 'Hydrocele Surgery', 'Kidney Removal Surgery',
        //                      'Kidney Blockage Surgery', 'Laser Kidney Stone Surgery'],
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO page_seo (page_name) VALUES (?)");
    foreach ($pages_grouped as $items) {
        foreach ($items as $p) $stmt->execute([$p]);
    }
} catch (Exception $e) {}

// Stats
$total_pages   = $pdo->query("SELECT COUNT(*) FROM page_seo")->fetchColumn();
$seo_complete  = $pdo->query("SELECT COUNT(*) FROM page_seo WHERE meta_title IS NOT NULL AND meta_title != '' AND meta_description IS NOT NULL AND meta_description != '' AND canonical_tag IS NOT NULL AND canonical_tag != ''")->fetchColumn();
$seo_partial   = $pdo->query("SELECT COUNT(*) FROM page_seo WHERE (meta_title IS NOT NULL AND meta_title != '') AND NOT (meta_description IS NOT NULL AND meta_description != '' AND canonical_tag IS NOT NULL AND canonical_tag != '')")->fetchColumn();
$total_views   = $pdo->query("SELECT COALESCE(SUM(views),0) FROM page_seo")->fetchColumn();
$top_page      = $pdo->query("SELECT page_name, views FROM page_seo ORDER BY views DESC LIMIT 1")->fetch();

// Filter & Search
$filter_group  = $_GET['group'] ?? 'All';
$search        = trim($_GET['search'] ?? '');
$sort          = $_GET['sort'] ?? 'views_desc';

$where_clauses = [];
$params = [];

if (!empty($search)) {
    $where_clauses[] = "(page_name LIKE ? OR meta_title LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$order_sql = match($sort) {
    'views_asc'    => 'views ASC',
    'name_asc'     => 'page_name ASC',
    'name_desc'    => 'page_name DESC',
    'updated_desc' => 'updated_at DESC',
    default        => 'views DESC',
};

$where_sql = $where_clauses ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
$all_pages_raw = $pdo->prepare("SELECT * FROM page_seo $where_sql ORDER BY $order_sql");
$all_pages_raw->execute($params);
$all_pages_flat = $all_pages_raw->fetchAll();

// Group pages for sidebar
$groups = ['Main Pages'];
$group_map = [];
foreach ($pages_grouped as $grp => $items) {
    foreach ($items as $p) $group_map[$p] = $grp;
}

$all_pages_flat = array_filter($all_pages_flat, function($pg) use ($group_map) {
    return ($group_map[$pg['page_name']] ?? '') === 'Main Pages';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pages Manager - IQH Admin</title>
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
        <!-- <img src="https://www.shreemanyatahospital.in/assets/img/Manyata_Logo.png" alt="IQH" class="w-10 h-10 bg-white rounded-lg object-contain"> -->
        <span class="text-xl font-display font-bold">Advenzatours Travel</span>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="dashboard.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-4 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>
        <a href="manage-posts.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-4 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Manage Posts</span>
        </a>
        <a href="manage-categories.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-4 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Categories</span>
        </a>
        <a href="pages.php" class="flex items-center space-x-3 text-white px-4 py-3 rounded-xl font-bold shadow-lg" style="background-color:#1171B9;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span>Pages</span>
        </a>
        <a href="page-seo.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-4 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>Page SEO</span>
        </a>

        <!-- Group Filter in Sidebar -->
        <div class="pt-4 border-t border-gray-800 mt-4">
            <p class="text-xs font-bold text-gray-500 uppercase px-4 mb-2">Filter by Group</p>
            <?php foreach ($groups as $grp): ?>
            <a href="?group=<?php echo urlencode($grp); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>"
               class="flex items-center justify-between px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $filter_group === $grp ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50'; ?>">
                <span><?php echo htmlspecialchars($grp); ?></span>
                <?php if ($grp !== 'All'): ?>
                <?php $grp_count = count(array_filter($pages_grouped[$grp] ?? [], fn($p) => true)); ?>
                <span class="text-xs bg-gray-700 px-1.5 py-0.5 rounded"><?php echo $grp_count; ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <a href="logout.php" class="mt-4 bg-red-500/10 text-red-500 px-6 py-3 rounded-xl font-bold border border-red-500/20 hover:bg-red-500 hover:text-white transition-all text-center flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        Logout
    </a>
</aside><!-- Main -->
<main class="flex-1 overflow-y-auto min-h-screen w-full">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 px-4 md:px-8 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <!-- Hamburger (mobile) -->
            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div>
                <h1 class="text-lg md:text-2xl font-display font-extrabold text-gray-900">Pages Manager</h1>
                <p class="text-xs text-gray-400 hidden md:block">All website pages - SEO settings &amp; view analytics</p>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-3">
            <a href="../index.php" target="_blank" class="text-gray-500 hover:text-gray-800 text-sm font-semibold hidden md:flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                View Site
            </a>
            <a href="page-seo.php" class="bg-orange-500 text-white font-bold text-xs md:text-sm px-3 md:px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                + Edit SEO
            </a>
        </div>
    </header>

    <div class="p-4 md:p-8 max-w-7xl mx-auto">

        <!-- Stats Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Total Pages</p>
                <p class="text-3xl font-display font-extrabold text-gray-900"><?php echo $total_pages; ?></p>
                <p class="text-xs text-gray-400 mt-1">across all sections</p>
            </div>
            <div class="bg-white rounded-2xl border border-green-200 p-5 shadow-sm bg-green-50">
                <p class="text-xs font-bold text-green-600 uppercase mb-1">SEO Complete</p>
                <p class="text-3xl font-display font-extrabold text-green-700"><?php echo $seo_complete; ?></p>
                <div class="mt-2 bg-green-200 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width:<?php echo $total_pages > 0 ? round(($seo_complete/$total_pages)*100) : 0; ?>%"></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-yellow-200 p-5 shadow-sm bg-yellow-50">
                <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Partial SEO</p>
                <p class="text-3xl font-display font-extrabold text-yellow-700"><?php echo $seo_partial; ?></p>
                <p class="text-xs text-yellow-500 mt-1">needs completion</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-200 p-5 shadow-sm bg-blue-50">
                <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total Page Views</p>
                <p class="text-3xl font-display font-extrabold text-blue-700"><?php echo number_format($total_views); ?></p>
                <?php if ($top_page): ?>
                <p class="text-[10px] text-blue-400 mt-1">Top: <?php echo htmlspecialchars($top_page['page_name']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Search + Sort Bar -->
        <form method="GET" class="flex gap-3 mb-6 items-center flex-wrap">
            <input type="hidden" name="group" value="<?php echo htmlspecialchars($filter_group); ?>">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search pages..." class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white">
            </div>
            <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="views_desc" <?php echo $sort === 'views_desc' ? 'selected' : ''; ?>>Most Views</option>
                <option value="views_asc"  <?php echo $sort === 'views_asc'  ? 'selected' : ''; ?>>Least Views</option>
                <option value="name_asc"   <?php echo $sort === 'name_asc'   ? 'selected' : ''; ?>>Name A-Z</option>
                <option value="name_desc"  <?php echo $sort === 'name_desc'  ? 'selected' : ''; ?>>Name Z-A</option>
                <option value="updated_desc" <?php echo $sort === 'updated_desc' ? 'selected' : ''; ?>>Recently Updated</option>
            </select>
            <button type="submit" class="bg-orange-500 text-white font-bold px-4 py-2.5 rounded-lg text-sm hover:bg-orange-600 transition-colors">Search</button>
            <?php if ($search || $filter_group !== 'All'): ?>
            <a href="pages.php" class="text-gray-400 hover:text-gray-700 text-sm font-medium">Clear</a>
            <?php endif; ?>
        </form>

        <!-- Pages Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="font-bold text-gray-800">
                    <?php echo htmlspecialchars($filter_group); ?> Pages
                    <span class="ml-2 text-sm font-normal text-gray-400">(<?php echo count($all_pages_flat); ?> shown)</span>
                </h3>
                <div class="flex gap-3 text-xs text-gray-400 font-semibold">
                    <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>Good</span>
                    <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-yellow-400"></span>Partial</span>
                    <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-red-400"></span>Missing</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Page</th>
                            <th class="px-5 py-3">Meta Title</th>
                            <th class="px-5 py-3">Meta Description</th>
                            <th class="px-5 py-3">Keywords</th>
                            <th class="px-5 py-3">Canonical</th>
                            <th class="px-5 py-3">Views</th>
                            <th class="px-5 py-3">SEO</th>
                            <th class="px-5 py-3">Updated</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($all_pages_flat)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p class="font-semibold">No pages found.</p>
                                <a href="page-seo.php" class="text-orange-500 hover:underline text-sm mt-1 inline-block">Set up Page SEO</a>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($all_pages_flat as $pg): ?>
                            <?php
                                $has_title    = !empty($pg['meta_title']);
                                $has_desc     = !empty($pg['meta_description']);
                                $has_keywords = !empty($pg['meta_keywords']);
                                $has_canonical= !empty($pg['canonical_tag']);
                                $seo_score    = ($has_title ? 1 : 0) + ($has_desc ? 1 : 0) + ($has_canonical ? 1 : 0);
                                $seo_pct      = round(($seo_score / 3) * 100);
                                if ($seo_score == 3)      [$seo_label, $seo_class, $dot_class] = ['Good',    'bg-green-100 text-green-700',  'bg-green-500'];
                                elseif ($seo_score >= 1)  [$seo_label, $seo_class, $dot_class] = ['Partial', 'bg-yellow-100 text-yellow-700','bg-yellow-400'];
                                else                      [$seo_label, $seo_class, $dot_class] = ['Missing', 'bg-red-100 text-red-600',      'bg-red-400'];
                                $pg_group = $group_map[$pg['page_name']] ?? 'Other';
                            ?>
                            <tr class="hover:bg-orange-50/30 transition-colors group">
                                <!-- Page Name -->
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block w-2 h-2 rounded-full <?php echo $dot_class; ?> shrink-0"></span>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($pg['page_name']); ?></div>
                                            <div class="text-[10px] text-gray-400 font-medium"><?php echo htmlspecialchars($pg_group); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Meta Title -->
                                <td class="px-5 py-3.5 max-w-[160px]">
                                    <?php if ($has_title): ?>
                                    <span class="text-xs text-gray-700 truncate block" title="<?php echo htmlspecialchars($pg['meta_title']); ?>">
                                        <?php echo htmlspecialchars(mb_substr($pg['meta_title'], 0, 35)) . (mb_strlen($pg['meta_title']) > 35 ? '…' : ''); ?>
                                    </span>
                                    <span class="text-[10px] text-gray-400"><?php echo mb_strlen($pg['meta_title']); ?> chars</span>
                                    <?php else: ?>
                                    <span class="text-xs text-red-400 italic font-medium">Not set</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Meta Description -->
                                <td class="px-5 py-3.5 max-w-[200px]">
                                    <?php if ($has_desc): ?>
                                    <span class="text-xs text-gray-600 truncate block" title="<?php echo htmlspecialchars($pg['meta_description']); ?>">
                                        <?php echo htmlspecialchars(mb_substr($pg['meta_description'], 0, 50)) . (mb_strlen($pg['meta_description']) > 50 ? '…' : ''); ?>
                                    </span>
                                    <span class="text-[10px] text-gray-400"><?php echo mb_strlen($pg['meta_description']); ?> chars</span>
                                    <?php else: ?>
                                    <span class="text-xs text-red-400 italic font-medium">Not set</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Keywords -->
                                <td class="px-5 py-3.5 max-w-[140px]">
                                    <?php if ($has_keywords): ?>
                                    <span class="text-xs text-gray-500 truncate block" title="<?php echo htmlspecialchars($pg['meta_keywords']); ?>">
                                        <?php echo htmlspecialchars(mb_substr($pg['meta_keywords'], 0, 30)) . (mb_strlen($pg['meta_keywords']) > 30 ? '…' : ''); ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-xs text-gray-300 italic">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Canonical -->
                                <td class="px-5 py-3.5">
                                    <?php if ($has_canonical): ?>
                                    <span class="inline-flex items-center gap-1 text-xs text-green-600 font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Set
                                    </span>
                                    <?php else: ?>
                                    <span class="text-xs text-gray-300">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Views -->
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span class="text-sm font-bold text-gray-800"><?php echo number_format($pg['views'] ?? 0); ?></span>
                                    </div>
                                </td>

                                <!-- SEO Badge + bar -->
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full <?php echo $seo_class; ?>"><?php echo $seo_label; ?></span>
                                    <div class="mt-1.5 bg-gray-100 rounded-full h-1 w-16">
                                        <div class="h-1 rounded-full <?php echo $seo_score == 3 ? 'bg-green-500' : ($seo_score >= 1 ? 'bg-yellow-400' : 'bg-red-400'); ?>" style="width:<?php echo $seo_pct; ?>%"></div>
                                    </div>
                                </td>

                                <!-- Updated -->
                                <td class="px-5 py-3.5 text-xs text-gray-400">
                                    <?php echo $pg['updated_at'] ? date('d M Y', strtotime($pg['updated_at'])) : '-'; ?>
                                </td>

                                <!-- Action -->
                                <td class="px-5 py-3.5 text-right">
    <a href="page-seo.php?page=<?php echo urlencode($pg['page_name']); ?>"
       class="inline-flex items-center gap-1.5 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-orange-600 transition-colors shadow-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Edit SEO
    </a>

    <!-- Delete -->
<a href="delete-page.php?page=<?php echo urlencode($pg['page_name']); ?>"
   onclick="return confirm('Delete this page?')"
   class="inline-flex items-center gap-1.5 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 transition-colors shadow-sm ml-2">
    
   <!-- Trash Icon -->
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-6 0h8m-9 3h10"></path>
    </svg>

    
</a>
</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
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
