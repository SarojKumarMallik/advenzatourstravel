<?php
ob_start(); // Prevent headers already sent notice from breaking redirects
require_once '../config/db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$post_id = $_GET['id'] ?? null;
$is_edit = $post_id !== null;

// AUTO-REPAIR: Ensure posts table and required columns exist
try {
    $exists = $pdo->query("SHOW TABLES LIKE 'posts'")->rowCount();
    if ($exists == 0) {
        $pdo->exec("CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content TEXT NOT NULL,
            excerpt TEXT,
            category_id INT,
            featured_image VARCHAR(500),
            image_alt VARCHAR(255),
            status ENUM('draft', 'published') DEFAULT 'draft',
            author_id INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } else {
        $cols = $pdo->query("SHOW COLUMNS FROM posts")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('excerpt', $cols)) $pdo->exec("ALTER TABLE posts ADD COLUMN excerpt TEXT AFTER content");
        if (!in_array('image_alt', $cols)) $pdo->exec("ALTER TABLE posts ADD COLUMN image_alt VARCHAR(255) AFTER featured_image");
        if (!in_array('author_id', $cols)) $pdo->exec("ALTER TABLE posts ADD COLUMN author_id INT AFTER status");
        if (!in_array('updated_at', $cols)) $pdo->exec("ALTER TABLE posts ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at");
    }
} catch (Exception $e) {}

// DIAGNOSTIC: Add ?debug=1 to your URL to see what's happening
if (isset($_GET['debug'])) {
    echo "<h1>Admin Debug Mode</h1>";
    echo "User ID: " . ($_SESSION['user_id'] ?? 'NULL') . "<br>";
    echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "POST Data: <pre>" . print_r($_POST, true) . "</pre>";
    }
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Get post data if editing
$post = null;
if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header('Location: manage-posts.php');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Uncomment the next line if you still can't see anything happening
    // die("POST Received: " . print_r($_POST, true)); 

    try {
        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        $status = $_POST['status'] ?? 'draft';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $meta_keywords = $_POST['meta_keywords'] ?? '';
        
        // Fix date format
        $publish_date = $_POST['publish_date'] ?? date('Y-m-d H:i:s');
        $publish_date = str_replace('T', ' ', $publish_date);
        if (strlen($publish_date) == 16) $publish_date .= ':00';
        
        $image_alt = $_POST['image_alt'] ?? '';
        $author_id = $_SESSION['user_id'] ?? 1; // Fallback to 1 if session lost
        
        // Handle image upload
        $featured_image = isset($post['featured_image']) ? $post['featured_image'] : '';
        
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_path)) {
                    $featured_image = 'uploads/' . $new_filename;
                    
                    if ($is_edit && !empty($post['featured_image']) && strpos($post['featured_image'], 'uploads/') === 0) {
                        $old_file = '../' . $post['featured_image'];
                        if (file_exists($old_file)) unlink($old_file);
                    }
                }
            }
        }
        
        if ($is_edit) {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, excerpt = ?, category_id = ?, featured_image = ?, image_alt = ?, status = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, created_at = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $slug, $content, $excerpt, $category_id, $featured_image, $image_alt, $status, $meta_title, $meta_description, $meta_keywords, $publish_date, $post_id]);
            header('Location: edit-post.php?id=' . $post_id . '&saved=1');
        } else {
            $stmt = $pdo->prepare("INSERT INTO posts (title, slug, content, excerpt, category_id, featured_image, image_alt, status, author_id, meta_title, meta_description, meta_keywords, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $slug, $content, $excerpt, $category_id, $featured_image, $image_alt, $status, $author_id, $meta_title, $meta_description, $meta_keywords, $publish_date]);
            $new_id = $pdo->lastInsertId();
            header('Location: edit-post.php?id=' . $new_id . '&saved=1');
        }
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        // If debug needed: die($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? 'Edit' : 'Create'; ?> Post - Travel Bee Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Quill Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
        #editor-container {
            height: 400px;
            background: white;
        }
        .ql-editor {
            font-size: 16px;
            min-height: 400px;
        }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-72 bg-gray-950 text-white h-screen sticky top-0 flex flex-col p-8">
    <div class="flex items-center space-x-3 mb-12">
        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-blue-500 rounded-lg flex items-center justify-center">
            <span class="text-white text-2xl">🐝</span>
        </div>
        <span class="text-xl font-display font-bold">Shree Manyata Hospital</span>
    </div>
    
    <nav class="flex-1 space-y-2">
        <a href="dashboard.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>
        <a href="manage-posts.php" class="flex items-center space-x-3 bg-orange-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-orange-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Manage Posts</span>
        </a>
        <a href="manage-categories.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Categories</span>
        </a>
        <a href="manage-destinations.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span>Destinations</span>
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
            <h1 class="text-3xl font-display font-extrabold text-gray-900"><?php echo $is_edit ? 'Edit' : 'Create New'; ?> Post</h1>
            <p class="text-gray-500 mt-1"><?php echo $is_edit ? 'Update your blog post' : 'Write and publish a new blog post'; ?></p>
        </div>
        <a href="manage-posts.php" class="text-gray-600 hover:text-gray-900 font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Posts</span>
        </a>
    </header>

    <?php if (isset($_GET['saved'])): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold">Post saved successfully!</span>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <span class="font-semibold block">Error saving post:</span>
            <span class="text-sm"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" id="postForm" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Post Title *</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title"
                        value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-lg font-semibold"
                        placeholder="Enter post title..."
                        onkeyup="generateSlug()"
                    >
                </div>

                <!-- Slug -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Slug *</label>
                    <input 
                        type="text" 
                        name="slug" 
                        id="slug"
                        value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent font-mono text-sm"
                        placeholder="post-url-slug"
                    >
                    <p class="text-xs text-gray-500 mt-2">URL-friendly version of the title</p>
                </div>

                <!-- Content with Quill Editor -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Content *</label>
                    <div id="editor-container" class="border border-gray-300 rounded-lg"></div>
                    <textarea name="content" id="content" style="display:none;"><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                </div>

                <!-- Excerpt -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Excerpt</label>
                    <textarea 
                        name="excerpt" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        placeholder="Short description for preview..."
                    ><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                    <p class="text-xs text-gray-500 mt-2">Brief summary shown in blog listings</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Publish</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select 
                                name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            >
                                <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                            </select>
                        </div>

                        <!-- Publish Date -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Publish Date</label>
                            <input 
                                type="datetime-local" 
                                name="publish_date" 
                                value="<?php echo $post ? date('Y-m-d\TH:i', strtotime($post['created_at'])) : date('Y-m-d\TH:i'); ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            >
                            <p class="text-xs text-gray-500 mt-1">Schedule or backdate your post</p>
                        </div>

                        <button 
                            type="submit" 
                            id="submit-btn"
                            class="w-full bg-orange-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20"
                        >
                            <span id="btn-text"><?php echo $is_edit ? 'Update Post' : 'Create Post'; ?></span>
                            <span id="btn-loading" class="hidden">
                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                        <?php if ($is_edit): ?>
                        <a 
                            href="../blog/<?php echo $post['slug']; ?>" 
                            target="_blank"
                            class="block w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-200 transition-all text-center"
                        >
                            Preview Post
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Category</h3>
                    <select 
                        name="category_id" 
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    >
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($post['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Featured Image Upload -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Featured Image</h3>
                    
                    <!-- Current Image Preview -->
                    <?php if (!empty($post['featured_image'])): ?>
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                        <img src="../<?php echo $post['featured_image']; ?>" alt="Current featured image" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                    </div>
                    <?php endif; ?>
                    
                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <?php echo !empty($post['featured_image']) ? 'Change Image' : 'Upload Image'; ?>
                        </label>
                        <input 
                            type="file" 
                            name="featured_image" 
                            id="featured_image"
                            accept="image/*"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                            onchange="previewImage(this)"
                        >
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF, or WebP (Max 5MB)</p>
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mb-4 hidden">
                        <p class="text-xs text-gray-500 mb-2">Preview:</p>
                        <img id="preview-img" src="" alt="Preview" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                    </div>

                    <!-- Image Alt Text -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Image Alt Text</label>
                        <input 
                            type="text" 
                            name="image_alt" 
                            value="<?php echo htmlspecialchars($post['image_alt'] ?? ''); ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Describe the image for accessibility"
                        >
                        <p class="text-xs text-gray-500 mt-1">Helps with SEO and accessibility</p>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="<?php echo htmlspecialchars($post['meta_title'] ?? ''); ?>"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="SEO Title (defaults to Title if empty)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" 
                                      placeholder="Brief description for search engines"><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($post['meta_keywords'] ?? ''); ?>"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="comma, separated, keywords">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
// Initialize Quill Editor
var quill = new Quill('#editor-container', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link', 'image', 'video'],
            ['blockquote', 'code-block'],
            ['clean']
        ]
    },
    placeholder: 'Write your post content here...'
});

// Set initial content if editing
var contentTextarea = document.getElementById('content');
if (contentTextarea.value) {
    quill.root.innerHTML = contentTextarea.value;
}

// Sync Quill content to textarea before form submission
var form = document.getElementById('postForm');
var submitBtn = document.getElementById('submit-btn');
var btnText = document.getElementById('btn-text');
var btnLoading = document.getElementById('btn-loading');

form.addEventListener('submit', function(e) {
    console.log('Form submit triggered');
    
    // Sync content from Quill to textarea
    var content = quill.root.innerHTML;
    contentTextarea.value = content;
    
    // Check if content is empty
    var textContent = quill.getText().trim();
    if (textContent.length === 0) {
        e.preventDefault();
        alert('Please add some content to your post!');
        return false;
    }
    
    // Show loading state
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    
    console.log('Form data synced, submitting...');
    return true;
});

function generateSlug() {
    const title = document.getElementById('title').value;
    const slug = title
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slug').value = slug;
}

function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>
