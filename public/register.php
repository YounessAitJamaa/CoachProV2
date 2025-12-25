<?php

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../classes/Coach.php';
    require_once __DIR__ . '/../classes/Sportif.php';
    require_once __DIR__ . '/../repositories/CoachRepository.php';
    require_once __DIR__ . '/../repositories/SportifRepository.php';

    $errors = [];

    if(isset($_POST['submit'])) {
        $database = new Database();
        $db = $database->getConnection();

        $role = $_POST['role'] ?? '';

        if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email'])) {
            $errors[] = "Tous les champs obligatoires doivent etre remplis.";
        }

        if(empty($errors)) {
            if ($role === 'coach') {
                $coach = new Coach (
                    0, $_POST['nom'], $_POST['prenom'], $_POST['email'],
                    $_POST['mot_de_passe'], date('Y-m-d'),
                    0, '../../assets/img/default.jpeg', 'Bio', 0, 'Debutant', 'A renseigner', '0000000000'
                );

                    $repo = new CoachRepository($db);
                    $success = $repo->register($coach);

            } else {

                $sportif = new Sportif(
                    $_POST['nom'], 
                    $_POST['prenom'], 
                    $_POST['email'],
                    $_POST['mot_de_passe'], 
                );

                $repo = new SportifRepository($db);
                $success = $repo->register($sportif);
            }

            if($success) {
                header('Location: login.php?registration=success');
                exit;
            }else {
                $errors[] = "";
            }

        }



    }



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Coach Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assests/styles/style.css">
</head>
<body class="min-h-screen bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Left Side - Hero Section -->
        <div class="hidden lg:flex lg:w-1/2 hero-gradient relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('/placeholder.svg?height=1080&width=1080')] bg-cover bg-center opacity-20"></div>
            
            <div class="relative z-10 flex flex-col justify-between p-12 text-white">
                <!-- Logo -->
                <div>
                    <h1 class="text-3xl font-bold">Coach Pro</h1>
                    <p class="text-gray-300 mt-2">Plateforme professionnelle de coaching sportif</p>
                </div>
                
                <!-- Main Content -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-5xl font-bold leading-tight mb-6">
                            Atteignez vos <span class="text-orange-500">objectifs</span><br />
                            avec les meilleurs coachs
                        </h2>
                        <p class="text-xl text-gray-300 leading-relaxed max-w-lg">
                            Rejoignez une communauté de professionnels et d'athlètes passionnés. 
                            Ensemble, dépassons les limites.
                        </p>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-700">
                        <div>
                            <div class="text-4xl font-bold text-orange-500">500+</div>
                            <div class="text-sm text-gray-400 mt-1">Coachs certifiés</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-orange-500">10K+</div>
                            <div class="text-sm text-gray-400 mt-1">Sportifs actifs</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-orange-500">98%</div>
                            <div class="text-sm text-gray-400 mt-1">Satisfaction</div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="text-sm text-gray-400">
                    © 2025 Coach Pro. Tous droits réservés.
                </div>
            </div>
        </div>
        
        <!-- Right Side - Registration Form -->
        <div class="flex-1 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Coach Pro</h1>
                </div>
                
                <!-- Form Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Créer votre compte</h2>
                    <p class="text-gray-600">Commencez votre parcours vers l'excellence</p>
                </div>
                
                <!-- Error Messages -->
                <!-- Error container -->
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

                
                <!-- Registration Form -->
                <form method="POST" class="space-y-6">
                    <!-- Name Fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom
                            </label>
                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>"
                                
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all"
                                placeholder="Dupont"
                            >
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                Prénom
                            </label>
                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>"
                                
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all"
                                placeholder="Jean"
                            >
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                            
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all"
                            placeholder="jean.dupont@exemple.fr"
                        >
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden">Format d'email invalide.</p>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="mot_de_passe" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <input
                            type="password"
                            id="mot_de_passe"
                            name="mot_de_passe"
                            
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all"
                            placeholder="••••••••"
                        >
                        <p class="mt-2 text-xs text-gray-500">Minimum 8 caractères recommandés</p>
                    </div>
                    
                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Je m'inscris en tant que
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Sportif Card -->
                            <div class="role-card">
                                <input
                                    type="radio"
                                    id="sportif"
                                    name="role"
                                    value="sportif"
                                    class="peer hidden"
                                    
                                    <?= (isset($_POST['role']) && $_POST['role'] === 'sportif') ? 'checked' : '' ?>
                                >
                                <label
                                    for="sportif"
                                    class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-500 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all"
                                >
                                    <svg class="w-10 h-10 mb-3 text-gray-600 peer-checked:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-semibold text-gray-900">Sportif</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Trouvez votre coach</span>
                                </label>
                            </div>
                            
                            <!-- Coach Card -->
                            <div class="role-card">
                                <input
                                    type="radio"
                                    id="coach"
                                    name="role"
                                    value="coach"
                                    class="peer hidden"
                                    
                                    <?= (isset($_POST['role']) && $_POST['role'] === 'coach') ? 'checked' : '' ?>
                                >
                                <label
                                    for="coach"
                                    class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-500 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all"
                                >
                                    <svg class="w-10 h-10 mb-3 text-gray-600 peer-checked:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <span class="font-semibold text-gray-900">Coach</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Partagez votre expertise</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button
                        type="submit"
                        name="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                    >
                        Créer mon compte
                    </button>
                    
                    <!-- Login Link -->
                    <p class="text-center text-sm text-gray-600">
                        Vous avez déjà un compte ? 
                        <a href="login.php" class="font-medium text-orange-500 hover:text-orange-600 hover:underline">
                            Se connecter
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
    <script>
        const jserrors = <?= json_encode($errors); ?>;

        if(jserrors.length > 0) {
            showErrors(jserrors);
        }
    </script>
</body>
</html>