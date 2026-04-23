<?php
// includes/footer.php
?>
<footer class="bg-gray-950 text-white pt-20 pb-10 mt-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-1 md:col-span-2 space-y-6">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-white text-2xl">🐝</span>
                    </div>
                    <span class="text-2xl font-display font-bold">Travel Bee</span>
                </div>
                <p class="text-gray-400 max-w-sm leading-relaxed">
                    Unveiling the world's most captivating corners, one hidden gem at a time. Join our community of dreamers and explorers.
                </p>
            </div>
            
            <div>
                <h4 class="text-lg font-bold mb-6">Quick Links</h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="blog.php" class="hover:text-orange-500 transition-colors">Latest Posts</a></li>
                    <li><a href="destinations.php" class="hover:text-orange-500 transition-colors">New Destinations</a></li>
                    <li><a href="about.php" class="hover:text-orange-500 transition-colors">Our Story</a></li>
                    <li><a href="contact.php" class="hover:text-orange-500 transition-colors">Support</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-bold mb-6">Connect</h4>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center hover:bg-orange-500 transition-all">FB</a>
                    <a href="#" class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center hover:bg-orange-500 transition-all">IG</a>
                    <a href="#" class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center hover:bg-orange-500 transition-all">TW</a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-900 pt-8 text-center text-gray-500 text-sm">
            &copy; <?php echo date('Y'); ?> Travel Bee. All adventures reserved.
        </div>
    </div>
</footer>

<script>
    // Simple intersection observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach((el) => observer.observe(el));
</script>
</body>
</html>
