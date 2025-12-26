<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoachPro - Trouvez votre coach sportif</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
    
    <!-- Hero Section -->
    <div class="min-h-screen flex flex-col">
        
        <!-- Navigation -->
        <!-- Made navigation responsive with mobile menu -->
        <nav class="absolute top-0 left-0 right-0 z-10 p-4 md:p-6">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-lg md:text-xl">
                        CP
                    </div>
                    <span class="text-xl md:text-2xl font-bold">CoachPro</span>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 text-white hover:text-orange-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="login.php" class="px-6 py-2 text-white hover:text-orange-400 transition-colors duration-200 font-medium">
                        Se connecter
                    </a>
                    <a href="register.php" class="px-6 py-2.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all duration-200 font-medium shadow-lg shadow-orange-500/30">
                        Créer un compte
                    </a>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 bg-slate-800/95 backdrop-blur-sm rounded-lg border border-white/10 overflow-hidden">
                <a href="login.php" class="block px-6 py-3 text-white hover:bg-white/10 transition-colors duration-200 font-medium border-b border-white/10">
                    Se connecter
                </a>
                <a href="register.php" class="block px-6 py-3 text-orange-400 hover:bg-white/10 transition-colors duration-200 font-medium">
                    Créer un compte
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <!-- Improved responsive spacing and text sizing -->
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 py-24 md:py-20">
            <div class="max-w-5xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-orange-500/10 border border-orange-500/20 rounded-full mb-6 md:mb-8">
                    <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                    <span class="text-orange-400 text-xs md:text-sm font-medium">Plateforme n°1 pour les coachs sportifs</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-4 md:mb-6 text-balance leading-tight">
                    Bienvenue sur 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">
                        CoachPro
                    </span>
                </h1>
                
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-300 mb-8 md:mb-12 text-balance max-w-3xl mx-auto px-4">
                    Trouvez votre coach sportif facilement et atteignez vos objectifs.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 md:gap-4 mb-12 md:mb-16 px-4">
                    <a href="register.php" class="px-6 md:px-8 py-3 md:py-4 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all duration-200 font-semibold text-base md:text-lg shadow-xl shadow-orange-500/30 w-full sm:w-auto text-center">
                        Commencer maintenant
                    </a>
                    <a href="login.php" class="px-6 md:px-8 py-3 md:py-4 bg-white/10 backdrop-blur-sm text-white rounded-lg hover:bg-white/20 transition-all duration-200 font-semibold text-base md:text-lg border border-white/20 w-full sm:w-auto text-center">
                        J'ai déjà un compte
                    </a>
                </div>

                <!-- Stats -->
                <!-- Made stats responsive with single column on mobile -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-8 max-w-4xl mx-auto mt-12 md:mt-20 px-4">
                    <div class="p-4 md:p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                        <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">500+</div>
                        <div class="text-sm md:text-base text-slate-300">Coachs professionnels</div>
                    </div>
                    <div class="p-4 md:p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                        <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">10K+</div>
                        <div class="text-sm md:text-base text-slate-300">Sportifs actifs</div>
                    </div>
                    <div class="p-4 md:p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                        <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">98%</div>
                        <div class="text-sm md:text-base text-slate-300">Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-4 md:py-6 text-center text-slate-400 text-xs md:text-sm px-4">
            <p>&copy; 2025 CoachPro. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>
