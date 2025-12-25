<?php 

    session_start();
    require_once  '../../config/database.php';
    require_once  '../../classes/Coach.php';
    require_once  '../../repositories/UserRepository.php';


    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header('Location: ../login/php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();

    $userRepo = new UserRepository($db);

    $userId = $_SESSION['user_id'];

    $userData = $userRepo->getUserById($userId);

    $coach = $userRepo->getCoachDetails($userData);
    
    $nom = $coach->getNom();
    $prenom = $coach->getPrenom();
    $biographie = $coach->getBiographie();
    $photo = $coach->getPhoto();
    $experience = $coach->getExperience();
    $niveau = $coach->getNiveau();

    $disciplines =  $userRepo->getCoachDisciplines($coach->getIdCoach());


?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Coach</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <?php  include '../../includes/header.php' ?>

    <div class="max-w-4xl mx-auto p-8">

    <!-- Updated header card with glassmorphism design -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6 flex items-center gap-6 mb-8 hover:border-orange-500/50 transition-all duration-300">
        <img 
            src="<?= htmlspecialchars($photo) ?>"
            alt="Photo coach"
            
            class="w-32 h-32 rounded-full object-cover border-4 border-orange-500 shadow-lg shadow-orange-500/20"
        >

        <div>
            <h1 class="text-3xl font-bold text-white">Coach <?= htmlspecialchars($prenom) .' '. htmlspecialchars($nom)?></h1>
            <p class="text-slate-400 mt-1">Coach sportif professionnel</p>

            <button
                class="mt-4 px-6 py-2 bg-orange-500/50 rounded-lg text-sm flex items-center gap-2 text-white"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                <a href="modifier_profile.php">Modifier le profil</a>
                
            </button>
        </div>
    </div>

    <!-- Updated bio card with glassmorphism design -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6 mb-6 hover:border-orange-500/50 transition-all duration-300">
        <div class="flex items-center gap-3 mb-3">
            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h2 class="text-xl font-semibold text-white">À propos</h2>
        </div>
        <p class="text-slate-300 leading-relaxed">
            <?= empty($biographie) ? 'Aucune biographie disponible' : htmlspecialchars($biographie) ?>
        </p>
    </div>

    <!-- Updated experience cards with glassmorphism and icon badges -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6 hover:border-orange-500/50 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-400 mb-1">Expérience</h2>
                    <p class="text-orange-500 text-3xl font-bold"><?= (!$experience) ? 0 : htmlspecialchars($experience) ?> ans</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6 hover:border-orange-500/50 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-400 mb-1">Niveau</h2>
                    <p class="text-white text-xl font-semibold"><?= (!$niveau) ? 'Aucun Niveau' : htmlspecialchars($niveau) ?></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Updated disciplines card with glassmorphism and improved tags -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6 hover:border-orange-500/50 transition-all duration-300">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <h2 class="text-xl font-semibold text-white">Disciplines</h2>
        </div>

        <div class="flex flex-wrap gap-3">
                <?php if (empty($disciplines)): ?>
                    <p class="text-slate-500 italic">Aucune discipline sélectionnée.</p>
                <?php else: ?>
                    <?php foreach ($disciplines as $d): ?>
                        <span class="px-4 py-2 bg-slate-900/50 text-orange-400 rounded-xl text-sm font-medium border border-slate-700 hover:border-orange-500/50 transition-colors">
                            <?= htmlspecialchars($d) ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>
</html>