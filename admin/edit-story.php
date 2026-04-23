<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
$is_edit = $id !== null;
$story = null;

if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM stories WHERE id = ?");
    $stmt->execute([$id]);
    $story = $stmt->fetch();
    if (!$story) {
        header('Location: manage-stories.php');
        exit;
    }
}

// Dynamically get doctor names from the doctors folder
$doctors = [];
$doctor_files = glob("../doctors/dr-*.php");
foreach ($doctor_files as $file) {
    $name = basename($file, ".php");
    $name = str_replace("dr-", "", $name);
    $name = str_replace("-", " ", $name);
    $name = ucwords($name);
    $doctors[] = "Dr. " . $name;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $_POST['heading'] ?? '';
    $video_link = $_POST['video_link'] ?? '';
    $doctor_name = $_POST['doctor_name'] ?? '';
    $story_date = $_POST['story_date'] ?? date('Y-m-d');
    $status = $_POST['status'] ?? 'draft';

    try {
        if ($is_edit) {
            $stmt = $pdo->prepare("UPDATE stories SET heading = ?, video_link = ?, doctor_name = ?, story_date = ?, status = ? WHERE id = ?");
            $stmt->execute([$heading, $video_link, $doctor_name, $story_date, $status, $id]);
            header("Location: manage-stories.php?updated=1");
        } else {
            $stmt = $pdo->prepare("INSERT INTO stories (heading, video_link, doctor_name, story_date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$heading, $video_link, $doctor_name, $story_date, $status]);
            header("Location: manage-stories.php?created=1");
        }
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? 'Edit Story' : 'Add New Story'; ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-72 bg-gray-950 text-white h-screen sticky top-0 flex flex-col p-8 shrink-0">
    <div class="flex items-center space-x-3 mb-12">
        <!-- <img src="https://www.shreemanyatahospital.in/assets/img/Manyata_Logo.png" alt="IQH" class="w-10 h-10 bg-white rounded-lg object-contain"> -->
        <span class="text-xl font-display font-bold">Advenzatours Travel</span>
    </div>
    <nav class="flex-1 space-y-2">
        <a href="dashboard.php" class="flex items-center space-x-3 text-gray-400 hover:text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>
        <a href="manage-stories.php" class="flex items-center space-x-3 text-white px-6 py-3 rounded-xl font-bold" style="background-color:#1171B9;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
            <span>Back to Stories</span>
        </a>
    </nav>
</aside>

<main class="flex-1 p-8">
    <header class="mb-8">
        <h1 class="text-3xl font-display font-extrabold text-gray-900"><?php echo $is_edit ? 'Edit Real Story' : 'Add New Real Story'; ?></h1>
        <p class="text-gray-500 mt-1">Fill in the details for the patient success story</p>
    </header>

    <?php if (isset($error)): ?>
    <div class="bg-red-50 text-red-800 p-4 rounded-xl mb-6 border border-red-200">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm max-w-2xl">
        <form method="POST" class="space-y-6">
            <!-- Heading -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Heading *</label>
                <input 
                    type="text" 
                    name="heading" 
                    value="<?php echo htmlspecialchars($story['heading'] ?? ''); ?>" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    placeholder="Enter story heading..."
                >
            </div>

            <!-- Video Link -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Video Link *</label>
                <input 
                    type="url" 
                    name="video_link" 
                    value="<?php echo htmlspecialchars($story['video_link'] ?? ''); ?>" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    placeholder="YouTube or Vimeo URL..."
                >
            </div>

            <!-- Doctor Name Dropdown -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Doctor Name *</label>
                <select name="doctor_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <option value="">Select Doctor</option>
                    <?php foreach ($doctors as $doc): ?>
                        <option value="<?php echo $doc; ?>" <?php echo ($story['doctor_name'] ?? '') === $doc ? 'selected' : ''; ?>>
                            <?php echo $doc; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Date *</label>
                <input 
                    type="date" 
                    name="story_date" 
                    value="<?php echo $story['story_date'] ?? date('Y-m-d'); ?>" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                >
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <option value="draft" <?php echo ($story['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo ($story['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                    <?php echo $is_edit ? 'Update Story' : 'Save Story'; ?>
                </button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
