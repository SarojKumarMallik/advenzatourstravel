<?php
session_start();
require_once '../config/db.php';

// Simple auth - create session if not exists
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Auto-login as admin for testing
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $category_id = $_POST['category_id'];
        $status = $_POST['status'];
        
        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Handle image
        $featured_image = 'https://via.placeholder.com/800x600';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                $featured_image = 'uploads/' . $filename;
            }
        }
        
        // Insert into database
        $sql = "INSERT INTO posts (title, slug, content, category_id, featured_image, status, author_id, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $slug, $content, $category_id, $featured_image, $status, $_SESSION['user_id']]);
        
        $message = "✅ Post created successfully! ID: " . $pdo->lastInsertId();
        
        // Clear form
        $_POST = [];
        
    } catch (Exception $e) {
        $error = "❌ Error: " . $e->getMessage();
    }
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Post - SIMPLE VERSION</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; color: #333; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        textarea { min-height: 200px; font-family: Arial, sans-serif; }
        input[type="file"] { padding: 5px; }
        button { background: #ff6600; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background: #ff8533; }
        .links { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
        .links a { color: #0066cc; text-decoration: none; margin-right: 15px; }
        .links a:hover { text-decoration: underline; }
        .required { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Create New Blog Post (SIMPLE VERSION)</h1>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" required placeholder="Enter post title...">
            </div>
            
            <div class="form-group">
                <label>Content <span class="required">*</span></label>
                <textarea name="content" required placeholder="Write your post content here..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Category <span class="required">*</span></label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Featured Image</label>
                <input type="file" name="image" accept="image/*">
                <small style="color: #666;">Optional - JPG, PNG, GIF (Max 5MB)</small>
            </div>
            
            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" required>
                    <option value="draft">Draft</option>
                    <option value="published" selected>Published</option>
                </select>
            </div>
            
            <button type="submit">✅ Create Post</button>
        </form>
        
        <div class="links">
            <a href="../blog.php" target="_blank">→ View Blog</a>
            <a href="manage-posts.php">→ Manage Posts</a>
            <a href="../test-blog.php" target="_blank">→ Test Database</a>
        </div>
    </div>
</body>
</html>
