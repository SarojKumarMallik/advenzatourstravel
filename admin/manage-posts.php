<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle post deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage-posts.php?deleted=1');
    exit;
}

// Get all posts
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter) {
    $sql .= " AND p.status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Get categories for filter
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - Travel Bee Admin</title>
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
        <!-- <img src="https://www.shreemanyatahospital.in/assets/img/Manyata_Logo.png" alt="IQH" class="w-10 h-10 bg-white rounded-lg object-contain"> -->
        <span class="text-xl font-display font-bold">Advenzatours Travel</span>
    </div>
    
    <nav class="flex-1 space-y-2">
        <a href="dashboard.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>
        <a href="manage-posts.php" class="flex items-center space-x-3 text-white px-6 py-3 rounded-xl font-bold shadow-lg" style="background-color:#1171B9;">
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
</aside>

<!-- Content -->
<main class="flex-1 p-8">
    <header class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-gray-900">Manage Posts</h1>
            <p class="text-gray-500 mt-1">Create, edit, and manage your blog posts</p>
        </div>
        <a href="edit-post.php" class="bg-orange-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Create New Post</span>
        </a>
    </header>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold">Post deleted successfully!</span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input 
                type="text" 
                name="search" 
                value="<?php echo htmlspecialchars($search); ?>" 
                placeholder="Search posts..." 
                class="flex-1 min-w-[300px] px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
            <select 
                name="status" 
                class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
                <option value="">All Status</option>
                <option value="published" <?php echo $status_filter === 'published' ? 'selected' : ''; ?>>Published</option>
                <option value="draft" <?php echo $status_filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
            </select>
            <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-800 transition-all">
                Search
            </button>
            <?php if ($search || $status_filter): ?>
            <a href="manage-posts.php" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                Clear
            </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Views</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="text-lg font-semibold">No posts found</p>
                            <p class="text-sm mt-1">Create your first post to get started</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <?php 
                                        $img_src = $post['featured_image'];
                                        if (!empty($img_src) && strpos($img_src, 'http') !== 0) {
                                            $img_src = '../' . $img_src;
                                        }
                                        if (empty($img_src)) $img_src = 'https://via.placeholder.com/150';
                                    ?>
                                    <img src="<?php echo $img_src; ?>" alt="" class="w-12 h-12 rounded-lg object-cover">
                                    <div>
                                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($post['title']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($post['slug']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600"><?php echo $post['category_name'] ?? 'Uncategorized'; ?></span>
                            </td>
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
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="../blog/<?php echo $post['slug']; ?>" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                        View
                                    </a>
                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="text-orange-600 hover:text-orange-700 font-semibold text-sm">
                                        Edit
                                    </a>
                                    <a href="?delete=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?')" class="text-red-600 hover:text-red-700 font-semibold text-sm">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>
