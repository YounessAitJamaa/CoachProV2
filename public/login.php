<?php

    session_start();
    
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../repositories/UserRepository.php';

    $errors = [];

    if(isset($_POST['submit'])) {
        $database = new Database();
        $db = $database->getConnection();

        if (empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
            $errors[] = "Tous les champs obligatoires doivent etre remplis.";
        }

        if(empty($errors)) {
            $userReop = new UserRepository($db);

            $email = $_POST['email'];
            $pass = $_POST['mot_de_passe'];

            $user = $userReop->login($email, $pass);

            if($user) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_nom'] = $user->getNom();
                $_SESSION['user_role'] = $user->getIdRole();

                if($user instanceof Coach) {
                    header('Location: coach/dashboard.php');
                } else {
                    header('Location: sportif/dashboard.php');
                }

                exit();
            } else {
                $errors = "Email ou mot de passe incorrect.";
            }
            
        } 
    }



?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Coach Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50">
    <div class="flex min-h-screen">
        <!-- Left Hero Section - Hidden on mobile -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-40"></div>
            
            <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">
                <div>
                    <div class="flex items-center gap-3 mb-16">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-xl">
                            CP
                        </div>
                        <span class="text-2xl font-bold">Coach Pro</span>
                    </div>
                    
                    <h1 class="text-5xl font-bold leading-tight mb-6">
                        Bon retour parmi nous
                    </h1>
                    <p class="text-xl text-slate-300 leading-relaxed max-w-md">
                        Connectez-vous pour accéder à votre espace personnel et continuer votre parcours sportif.
                    </p>
                </div>
                
                <div class="grid grid-cols-3 gap-8 pt-8 border-t border-slate-700">
                    <div>
                        <div class="text-3xl font-bold text-orange-500 mb-1">500+</div>
                        <div class="text-sm text-slate-400">Coachs actifs</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-orange-500 mb-1">10K+</div>
                        <div class="text-sm text-slate-400">Sportifs</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-orange-500 mb-1">98%</div>
                        <div class="text-sm text-slate-400">Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="flex items-center gap-3 mb-8 lg:hidden">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-xl text-white">
                        CP
                    </div>
                    <span class="text-2xl font-bold text-slate-900">Coach Pro</span>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Connexion</h2>
                    <p class="text-slate-600">Accédez à votre compte</p>
                </div>

               <div id="success-container" class="hidden mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Success icon -->
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Succès</h3>
                            <p id="success-message" class="mt-2 text-sm text-green-700"></p>
                        </div>
                    </div>
                </div>
                
                <!-- error container -->
                <div id="error-container" class="hidden mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erreurs détectées</h3>
                            <ul id="error-list" class="mt-2 text-sm text-red-700 list-disc list-inside">
                                
                            </ul>
                        </div>
                    </div>
                </div>

                <form method="POST" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            Adresse email
                        </label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            placeholder="votre@email.com" 
                            required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none text-slate-900 placeholder:text-slate-400"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                            Mot de passe
                        </label>
                        <input 
                            type="password" 
                            id="password"
                            name="mot_de_passe" 
                            placeholder="••••••••" 
                            required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none text-slate-900 placeholder:text-slate-400"
                        >
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 text-orange-500 border-slate-300 rounded focus:ring-orange-500">
                            <span class="text-slate-600">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="text-orange-500 hover:text-orange-600 font-medium transition-colors">
                            Mot de passe oublié?
                        </a>
                    </div>

                    <button 
                        type="submit" 
                        name="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 hover:-translate-y-0.5"
                    >
                        Se connecter
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-slate-600">
                        Pas encore de compte? 
                        <a href="register.php" class="text-orange-500 hover:text-orange-600 font-semibold transition-colors">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>