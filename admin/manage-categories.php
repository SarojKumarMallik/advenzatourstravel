<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// AUTO-REPAIR: Check if 'description' column exists, if not, add it
try {
    $checkCols = $pdo->query("SHOW COLUMNS FROM categories LIKE 'description'")->fetch();
    if (!$checkCols) {
        $pdo->exec("ALTER TABLE categories ADD COLUMN description TEXT AFTER slug");
    }
} catch (Exception $e) {
    // Silent fail if repair not possible, error will show below
}

$error_message = '';

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    try {
        $name = $_POST['name'];
        $slug = $_POST['slug'];
        $description = $_POST['description'];
        
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $slug, $description]);
        header('Location: manage-categories.php?created=1');
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Handle category update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $slug = $_POST['slug'];
        $description = $_POST['description'];
        
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $description, $id]);
        header('Location: manage-categories.php?updated=1');
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Handle category deletion
if (isset($_GET['delete'])) {
    try {
        $id = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: manage-categories.php?deleted=1');
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Get all categories with post count
$categories = $pdo->query("
    SELECT c.*, COUNT(p.id) as post_count 
    FROM categories c 
    LEFT JOIN posts p ON c.id = p.category_id 
    GROUP BY c.id 
    ORDER BY c.name
")->fetchAll();

// Get category for editing
$edit_category = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_category = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Travel Bee Admin</title>
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
        <a href="manage-posts.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Manage Posts</span>
        </a>
        <a href="manage-categories.php" class="flex items-center space-x-3 text-white px-6 py-3 rounded-xl font-bold shadow-lg" style="background-color:#1171B9;">
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
    <header class="mb-8">
        <h1 class="text-3xl font-display font-extrabold text-gray-900">Manage Categories</h1>
        <p class="text-gray-500 mt-1">Organize your blog posts with categories</p>
    </header>
    
    <?php if ($error_message): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-semibold">Error: <?php echo htmlspecialchars($error_message); ?></span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['created'])): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-semibold">Category created successfully!</span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-xl mb-6 flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-semibold">Category updated successfully!</span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-semibold">Category deleted successfully!</span>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-8">
                <h2 class="text-xl font-bold mb-6"><?php echo $edit_category ? 'Edit' : 'Add New'; ?> Category</h2>
                <form method="POST">
                    <?php if ($edit_category): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="cat-name"
                                value="<?php echo $edit_category['name'] ?? ''; ?>" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g., Adventure"
                                onkeyup="generateCategorySlug()"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Slug *</label>
                            <input 
                                type="text" 
                                name="slug" 
                                id="cat-slug"
                                value="<?php echo $edit_category['slug'] ?? ''; ?>" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 font-mono text-sm"
                                placeholder="adventure"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea 
                                name="description" 
                                rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Brief description..."
                            ><?php echo $edit_category['description'] ?? ''; ?></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button 
                                type="submit" 
                                name="<?php echo $edit_category ? 'update' : 'create'; ?>"
                                class="flex-1 bg-orange-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-600 transition-all"
                            >
                                <?php echo $edit_category ? 'Update' : 'Create'; ?>
                            </button>
                            <?php if ($edit_category): ?>
                            <a href="manage-categories.php" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition-all">
                                Cancel
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-bold text-lg">All Categories (<?php echo count($categories); ?>)</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php if (empty($categories)): ?>
                    <div class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <p class="text-lg font-semibold">No categories yet</p>
                        <p class="text-sm mt-1">Create your first category to organize posts</p>
                    </div>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                        <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h4 class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($category['name']); ?></h4>
                                        <span class="px-2.5 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                            <?php echo $category['post_count']; ?> posts
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars(($category['description'] ?? '') ?: 'No description'); ?></p>
                                    <p class="text-xs text-gray-400 font-mono">/blog?category=<?php echo $category['slug']; ?></p>
                                </div>
                                <div class="flex items-center space-x-3 ml-4">
                                    <a href="?edit=<?php echo $category['id']; ?>" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                        Edit
                                    </a>
                                    <a 
                                        href="?delete=<?php echo $category['id']; ?>" 
                                        onclick="return confirm('Delete this category? Posts will not be deleted.')" 
                                        class="text-red-600 hover:text-red-700 font-semibold text-sm"
                                    >
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function generateCategorySlug() {
    const name = document.getElementById('cat-name').value;
    const slug = name
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('cat-slug').value = slug;
}
</script>

</body>
</html>
