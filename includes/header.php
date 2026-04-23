<?php include 'seo_tags.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
        .gradient-text {
            background: linear-gradient(to r, #f0710b, #0ba5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-[#fafafa] text-gray-900">

<header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 h-20 flex items-center justify-between">
        <a href="index.php" class="flex items-center space-x-2 group">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-blue-500 rounded-lg flex items-center justify-center transform group-hover:rotate-12 transition-transform">
                <span class="text-white text-2xl">🐝</span>
            </div>
            <span class="text-2xl font-display font-extrabold gradient-text">Travel Bee</span>
        </a>

        <div class="hidden md:flex items-center space-x-8 font-medium">
            <a href="index.php" class="hover:text-orange-500 transition-colors">Home</a>
            <a href="blog.php" class="hover:text-orange-500 transition-colors">Blog</a>
            <a href="destinations.php" class="hover:text-orange-500 transition-colors">Destinations</a>
            <a href="about.php" class="hover:text-orange-500 transition-colors">About</a>
            <a href="contact.php" class="hover:text-orange-500 transition-colors">Contact</a>
            <a href="contact.php" class="bg-orange-500 text-white px-6 py-2.5 rounded-full hover:bg-orange-600 transition-all shadow-md active:scale-95">Subscribe</a>
        </div>

        <button class="md:hidden text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </nav>
</header>
