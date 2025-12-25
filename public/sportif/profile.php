<?php

    session_start();
    require_once  '../../config/database.php';
    require_once  '../../classes/Sportif.php';
    require_once  '../../repositories/UserRepository.php';
    require_once  '../../repositories/SeanceRepository.php';


    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
        header('Location: ../login.php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();



    $userRepo = new UserRepository($db);
    $seanceRepo = new SeanceRepository($db);


    $userId = $_SESSION['user_id'];
    $userData = $userRepo->getUserById($userId);

    $sportif = $userRepo->getSportifDetails($userData);

    $nom = $sportif->getNom();
    $prenom = $sportif->getPrenom();
    $email = $sportif->getEmail();
    $photo = $sportif->getPhoto() ?: '../../assets/img/default.jpeg';
    $telephone = $sportif->getTelephone();

    $resultTotal = $seanceRepo->getSportifSessionsCount($userId, 'accepte') + $seanceRepo->getSportifSessionsCount($userId, 'en attente'); 
    $totalFinished =  $seanceRepo->getSportifSessionsCompletesCount($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

<?php include '../../includes/header.php' ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-10">

    <!-- Updated header with glassmorphism design and improved layout -->
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Mon profil</h1>
        <a href="modifier_profile.php"
           class="bg-orange-500 hover:bg-orange-400 text-slate-900 font-semibold px-4 sm:px-5 py-2 rounded-lg transition flex items-center gap-2 text-sm sm:text-base">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span class="hidden sm:inline">Modifier</span>
        </a>
    </div>

    <!-- Updated Profile Card with glassmorphism design and icon badge -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-10 hover:border-orange-500/50 transition-all duration-300">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 lg:gap-8">
            <img
                src="<?= htmlspecialchars($photo) ?>"
                class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 rounded-full object-cover border-4 border-orange-500 shadow-lg shadow-orange-500/20"
                alt="Sportif"
            >

            <div class="text-center sm:text-left">
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-1"><?= htmlspecialchars($prenom) .' '. htmlspecialchars($nom) ?></h2>
                <p class="text-slate-400 mb-3 sm:mb-4 text-sm sm:text-base"><?= htmlspecialchars($email) ?></p>

                <span class="inline-block bg-orange-500/20 text-orange-400 px-4 py-1 rounded-lg text-sm border border-orange-500/30">
                    Sportif
                </span>
            </div>
        </div>
    </div>

    <!-- Updated Stats with icon badges and glassmorphism -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-10">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 hover:border-orange-500/50 transition-all duration-300">
            <div class="flex items-center gap-3 sm:gap-4 mb-2">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs sm:text-sm">Séances réservées</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white"><?= htmlspecialchars($resultTotal) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 hover:border-orange-500/50 transition-all duration-300">
            <div class="flex items-center gap-3 sm:gap-4 mb-2">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs sm:text-sm">Séances terminées</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white"><?= htmlspecialchars($totalFinished) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 hover:border-orange-500/50 transition-all duration-300 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-3 sm:gap-4 mb-2">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs sm:text-sm">Avis donnés</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white">4</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Updated Personal Info with glassmorphism and section icon -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 lg:p-8 hover:border-orange-500/50 transition-all duration-300">
        <div class="flex items-center gap-3 mb-4 sm:mb-6">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 class="text-lg sm:text-xl font-semibold text-white">Informations personnelles</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <p class="text-slate-400 text-xs sm:text-sm mb-1">Nom complet</p>
                <p class="font-medium text-white text-sm sm:text-base"><?= htmlspecialchars($prenom) .' '. htmlspecialchars($nom) ?></p>
            </div>

            <div>
                <p class="text-slate-400 text-xs sm:text-sm mb-1">Email</p>
                <p class="font-medium text-white text-sm sm:text-base break-all"><?= htmlspecialchars($email) ?></p>
            </div>

            <div>
                <p class="text-slate-400 text-xs sm:text-sm mb-1">Téléphone</p>
                <p class="font-medium text-white text-sm sm:text-base"><?= htmlspecialchars($telephone) ?></p>
            </div>
        </div>
    </div>

</div>

</body>
</html>
