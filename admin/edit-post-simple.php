<?php
session_start();
require_once '../config/db.php';

// Simple auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$post_id = $_GET['id'] ?? null;
$is_edit = $post_id !== null;
$error = '';
$success = '';

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
    try {
        // Get form data
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $excerpt = trim($_POST['excerpt'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $status = $_POST['status'] ?? 'draft';
        $image_alt = trim($_POST['image_alt'] ?? '');
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $meta_keywords = trim($_POST['meta_keywords'] ?? '');
        
        // Validation
        if (empty($title)) throw new Exception('Title is required');
        if (empty($slug)) throw new Exception('Slug is required');
        if (empty($content)) throw new Exception('Content is required');
        if (empty($category_id)) throw new Exception('Category is required');
        
        // Handle image upload
        $featured_image = $is_edit && isset($post['featured_image']) ? $post['featured_image'] : '';
        
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_path)) {
                    // Delete old image
                    if ($is_edit && !empty($post['featured_image']) && file_exists('../' . $post['featured_image'])) {
                        @unlink('../' . $post['featured_image']);
                    }
                    $featured_image = 'uploads/' . $new_filename;
                }
            }
        }
        
        if ($is_edit) {
            // Update
            $stmt = $pdo->prepare("
                UPDATE posts 
                SET title = ?, slug = ?, content = ?, excerpt = ?, category_id = ?, 
                    featured_image = ?, image_alt = ?, status = ?, 
                    meta_title = ?, meta_description = ?, meta_keywords = ?,
                    updated_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([
                $title, $slug, $content, $excerpt, $category_id, 
                $featured_image, $image_alt, $status, 
                $meta_title, $meta_description, $meta_keywords,
                $post_id
            ]);
            $success = 'Post updated successfully!';
            
            // Refresh post data
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$post_id]);
            $post = $stmt->fetch();
        } else {
            // Insert
            $stmt = $pdo->prepare("
                INSERT INTO posts (
                    title, slug, content, excerpt, category_id, 
                    featured_image, image_alt, status, author_id, 
                    meta_title, meta_description, meta_keywords,
                    created_at, updated_at
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([
                $title, $slug, $content, $excerpt, $category_id, 
                $featured_image, $image_alt, $status, $_SESSION['user_id'],
                $meta_title, $meta_description, $meta_keywords
            ]);
            $new_id = $pdo->lastInsertId();
            
            // Redirect to edit mode
            header('Location: edit-post.php?id=' . $new_id . '&success=created');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['success'])) {
    $success = $_GET['success'] === 'created' ? 'Post created successfully!' : 'Post saved successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? 'Edit' : 'Create'; ?> Post - Travel Bee Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        #editor { height: 400px; }
    </style>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white p-6">
        <h1 class="text-xl font-bold mb-8">Travel Bee Admin</h1>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block px-4 py-2 rounded hover:bg-gray-800">Dashboard</a>
            <a href="manage-posts.php" class="block px-4 py-2 rounded bg-orange-500">Manage Posts</a>
            <a href="manage-categories.php" class="block px-4 py-2 rounded hover:bg-gray-800">Categories</a>
            <a href="logout.php" class="block px-4 py-2 rounded hover:bg-red-600 mt-8">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold"><?php echo $is_edit ? 'Edit' : 'Create'; ?> Post</h2>
                <a href="manage-posts.php" class="text-blue-600 hover:underline">← Back to Posts</a>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Title -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <label class="block font-bold mb-2">Title *</label>
                    <input type="text" name="title" id="title" required
                           value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>"
                           class="w-full border rounded px-4 py-2"
                           onkeyup="generateSlug()">
                </div>

                <!-- Slug -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <label class="block font-bold mb-2">URL Slug *</label>
                    <input type="text" name="slug" id="slug" required
                           value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>"
                           class="w-full border rounded px-4 py-2 font-mono text-sm">
                </div>

                <!-- Content -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <label class="block font-bold mb-2">Content *</label>
                    <div id="editor"></div>
                    <textarea name="content" id="content" required style="display:none;"><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                </div>

                <!-- Excerpt -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <label class="block font-bold mb-2">Excerpt</label>
                    <textarea name="excerpt" rows="3" class="w-full border rounded px-4 py-2"><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Category -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <label class="block font-bold mb-2">Category *</label>
                        <select name="category_id" required class="w-full border rounded px-4 py-2">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($post['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <label class="block font-bold mb-2">Status *</label>
                        <select name="status" class="w-full border rounded px-4 py-2">
                            <option value="draft" <?php echo ($post['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <label class="block font-bold mb-2">Featured Image</label>
                    
                    <?php if (!empty($post['featured_image'])): ?>
                    <div class="mb-4">
                        <img src="../<?php echo $post['featured_image']; ?>" class="w-full h-48 object-cover rounded">
                    </div>
                    <?php endif; ?>
                    
                    <input type="file" name="featured_image" accept="image/*" class="w-full border rounded px-4 py-2">
                    
                    <div class="mt-4">
                        <label class="block font-bold mb-2">Image Alt Text</label>
                        <input type="text" name="image_alt" value="<?php echo htmlspecialchars($post['image_alt'] ?? ''); ?>"
                               class="w-full border rounded px-4 py-2" placeholder="Describe the image">
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-bold mb-4">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-bold mb-2 text-sm">Meta Title</label>
                            <input type="text" name="meta_title" value="<?php echo htmlspecialchars($post['meta_title'] ?? ''); ?>"
                                   class="w-full border rounded px-4 py-2" placeholder="SEO Title (defaults to Title if empty)">
                        </div>
                        
                        <div>
                            <label class="block font-bold mb-2 text-sm">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full border rounded px-4 py-2" 
                                      placeholder="Brief description for search engines"><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block font-bold mb-2 text-sm">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($post['meta_keywords'] ?? ''); ?>"
                                   class="w-full border rounded px-4 py-2" placeholder="comma, separated, keywords">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-orange-600">
                        <?php echo $is_edit ? 'Update Post' : 'Create Post'; ?>
                    </button>
                    <?php if ($is_edit): ?>
                    <a href="../blog/<?php echo $post['slug']; ?>" target="_blank" 
                       class="bg-blue-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-600">
                        Preview
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
// Initialize Quill
var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            ['link', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

// Load existing content
var content = document.getElementById('content').value;
if (content) {
    quill.root.innerHTML = content;
}

// Sync on submit
document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('content').value = quill.root.innerHTML;
});

// Generate slug
function generateSlug() {
    var title = document.getElementById('title').value;
    var slug = title.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slug').value = slug;
}
</script>

</body>
</html>
