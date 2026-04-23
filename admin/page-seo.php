<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ensure table exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS page_seo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_name VARCHAR(50) UNIQUE NOT NULL,
        page_title VARCHAR(255),
        meta_title VARCHAR(255),
        meta_description TEXT,
        meta_keywords TEXT,
        canonical_tag VARCHAR(255),
        analytics_code TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Check if pages exist
    $pages_grouped = [
        'Main Pages' => ['Home', 'About', 'Blog', 'Contact', 'service','sambalpur-bolangir-tour-package','puri-konark-tour-package','package','koraput-tour-package','gallery','daringibadi-tour-package'],
        // 'Doctors' => [
        //     'Dr. Bhushan Chittawadagi', 'Dr. Dileep M', 'Dr. Mahendra Madesh', 
        //     'Dr. Mohammed Muzamil', 'Dr. Naveen Ganjoo', 'Dr. Pruthvi Raj', 
        //     'Dr. Raksha Madhu', 'Dr. Manoj Karthik'
        // ],
        // 'General Surgery' => [
        //     'General Surgery', 'Appendix Surgery', 'Fissure Treatment', 'Fistula Treatment', 
        //     'Gallbladder Removal', 'Hernia Surgery', 'Laser Piles Surgery', 
        //     'Piles Treatment', 'Pilonidal Sinus Treatment', 'Umbilical Hernia Treatment', 
        //     'Varicose Veins Treatment'
        // ],
        // 'Gastro & Liver' => [
        //     'Gastro & Liver', 'Chronic Intestine Inflammation', 'Hepatitis Treatment', 
        //     'IBD Treatment', 'Liver Disease Treatment'
        // ],
        // 'Neurology' => [
        //     'Neurosurgery', 'Brain Tumor & Cranial Procedures', 'Functional Neurosurgery', 
        //     'Hydrocephalus & CSF Flow', 'Neuro-trauma & Emergency', 
        //     'Neurovascular Surgery', 'Spine Neurosurgery'
        // ],
        // 'OBG' => [
        //     'OBG', 'Pregnancy Care', 'Uterus Removal Surgery', 'Breast Infection Treatment', 
        //     'Endometriosis Treatment', 'Fibroid Removal Surgery', 'Infertility Treatment',
        //     'Ovarian Cyst Removal', 'Urine Leakage Treatment', 'Uterus Cleaning Procedure'
        // ],
        // 'Orthopedics' => [
        //     'Orthopaedics', 'ACL Reconstruction Surgery', 'PCL Reconstruction Surgery',
        //     'Hip Replacement Surgery', 'Knee Arthroscopy', 'Knee Replacement Surgery', 
        //     'Shoulder Arthroscopy', 'Shoulder Replacement Surgery', 
        //     'Slip Disc Surgery', 'Spine Surgery'
        // ],
        // 'Urology' => [
        //     'Urology', 'Kidney Stone Removal', 'Prostate Surgery', 'Ureteric Stone Removal', 
        //     'Circumcision Surgery', 'Hydrocele Surgery', 'Kidney Removal Surgery', 
        //     'Kidney Blockage Surgery', 'Laser Kidney Stone Surgery'
        // ]
    ];

    // Flatten for DB insert
    $flat_pages = [];
    foreach ($pages_grouped as $group => $items) {
        $flat_pages = array_merge($flat_pages, $items);
    }
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO page_seo (page_name) VALUES (?)");
    foreach ($flat_pages as $page) {
        $stmt->execute([$page]);
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

$message = '';
$selected_page = $_GET['page'] ?? 'Home';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_seo'])) {
    $page_name = $_POST['page_name'];
    $page_title = $_POST['page_title'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];
    $canonical_tag = $_POST['canonical_tag'];
    $analytics_code = $_POST['analytics_code'] ?? '';

    $stmt = $pdo->prepare("UPDATE page_seo SET 
        page_title = ?, 
        meta_title = ?, 
        meta_description = ?, 
        meta_keywords = ?, 
        canonical_tag = ?, 
        analytics_code = ? 
        WHERE page_name = ?");
    
    if ($stmt->execute([$page_title, $meta_title, $meta_description, $meta_keywords, $canonical_tag, $analytics_code, $page_name])) {
        $message = "SEO settings updated successfully for $page_name!";
        $selected_page = $page_name;
    }
}

// Fetch current page data
$stmt = $pdo->prepare("SELECT * FROM page_seo WHERE page_name = ?");
$stmt->execute([$selected_page]);
$seo_data = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page SEO Settings - Travel Bee Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-72 bg-gray-950 text-white h-screen sticky top-0 flex flex-col p-8">
    <div class="flex items-center space-x-3 mb-12">
        <img src="https://www.shreemanyatahospital.in/assets/img/Manyata_Logo.png" alt="IQH" class="w-10 h-10 bg-white rounded-lg object-contain">
        <span class="text-xl font-display font-bold">Advenzatours Travel</span>
    </div>
    
    <nav class="flex-1 space-y-2">
        <a href="dashboard.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
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
        <a href="page-seo.php" class="flex items-center space-x-3 text-white px-6 py-3 rounded-xl font-bold shadow-lg" style="background-color:#1171B9;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>Page SEO</span>
        </a>
    </nav>
    
    <a href="logout.php" class="bg-red-500/10 text-red-500 px-6 py-3 rounded-xl font-bold border border-red-500/20 hover:bg-red-500 hover:text-white transition-all text-center">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        Logout
    </a>
</aside>

<!-- Content -->
<main class="flex-1 p-8">
    <header class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-gray-900">Page SEO Settings</h1>
            <p class="text-gray-500 mt-1">Manage search engine optimization for your pages</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="../index.php" target="_blank" class="text-gray-600 hover:text-gray-900 font-semibold text-sm flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>View Site</span>
            </a>
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg">
                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
            </div>
        </div>
    </header>

    <?php if ($message): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-semibold"><?php echo $message; ?></span>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-lg text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                Select Page to Edit
            </h3>
            <div class="flex items-center space-x-3">
                <form method="GET" class="flex items-center">
                    <select name="page" onchange="this.form.submit()" class="bg-gray-950 text-white px-4 py-2.5 rounded-lg font-bold focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer appearance-none text-center min-w-[120px]">
                        <?php foreach ($pages_grouped as $group => $items): ?>
                            <optgroup label="<?php echo $group; ?>" class="bg-gray-800 text-orange-400">
                                <?php foreach ($items as $p): ?>
                                    <option value="<?php echo $p; ?>" class="bg-gray-950 text-white" <?php echo $selected_page === $p ? 'selected' : ''; ?>><?php echo $p; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                    <!-- Custom Arrow for better control -->
                    <div class="relative -ml-8 pointer-events-none text-white opacity-60">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" class="p-8 space-y-6">
            <input type="hidden" name="page_name" value="<?php echo $selected_page; ?>">
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Page Title -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Page Title:</label>
                    <input type="text" name="page_title" 
                        value="<?php echo htmlspecialchars($seo_data['page_title'] ?? ''); ?>" 
                        placeholder="Enter page title..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                </div>

                <!-- Meta Title -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Meta Title:</label>
                    <input type="text" name="meta_title" 
                        value="<?php echo htmlspecialchars($seo_data['meta_title'] ?? ''); ?>" 
                        placeholder="Enter meta title..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                </div>

                <!-- Meta Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Meta Description:</label>
                    <textarea name="meta_description" rows="3" 
                        placeholder="Enter meta description..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"><?php echo htmlspecialchars($seo_data['meta_description'] ?? ''); ?></textarea>
                </div>

                <!-- Meta Keyword -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Meta Keyword:</label>
                    <input type="text" name="meta_keywords" 
                        value="<?php echo htmlspecialchars($seo_data['meta_keywords'] ?? ''); ?>" 
                        placeholder="Keywords separated by commas..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                </div>

                <!-- Canonical Tag -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Canonical Tag:</label>
                    <input type="text" name="canonical_tag" 
                        value="<?php echo htmlspecialchars($seo_data['canonical_tag'] ?? ''); ?>" 
                        placeholder="https://example.com/page"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                </div>

                <!-- Analytics ID (Only for Home) -->
                <?php if ($selected_page === 'Home'): ?>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Google Analytics ID </label>
                    <input type="text" name="analytics_code" 
                        value="<?php echo htmlspecialchars($seo_data['analytics_code'] ?? ''); ?>" 
                        placeholder="e.g. G-XXXXXXXXXX"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all font-mono text-sm">
                    <p class="text-xs text-gray-500 mt-1">Enter your Measurement ID (starts with G-). This will only be active on the Home page.</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="pt-4">
                <button type="submit" name="save_seo" class="bg-orange-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span>Save SEO Settings</span>
                </button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
