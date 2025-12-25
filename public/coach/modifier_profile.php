<?php

    session_start();
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../classes/Coach.php';
    require_once  '../../repositories/UserRepository.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header('Location: ../login.php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $userRepo = new UserRepository($db);

    $userId = $_SESSION['user_id'];

    $userData = $userRepo->getUserById($userId);
    $coach = $userRepo->getCoachDetails($userData);

    $photo = $coach->getPhoto();
    $biographie = $coach->getBiographie();
    $experience = $coach->getExperience();
    $niveau = $coach->getNiveau();

    $alldisciplines = $userRepo->getAllDisciplines();
    $currentDiscIds = $userRepo->getCoachDisciplinesIds($coach->getIdCoach());

    if(isset($_POST['submit'])) {
        $coach->setPhoto($_POST['photo']);
        $coach->setBiographie($_POST['biographie']);
        $coach->setExperience((int)$_POST['experience']);
        $coach->setNiveau($_POST['niveau']);

        $selectedDisciplines = $_POST['disciplines'] ?? [];

        if($userRepo->updateCoach($coach, $selectedDisciplines)){
            header('Location: modifier_profile.php?success=1');
            exit();
        }

    }


?>










<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier profil - CoachPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <!-- Added navigation header to match coach.html design -->
    <nav class="bg-slate-900/50 backdrop-blur-sm border-b border-slate-700/50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white">Coach<span class="text-orange-500">Pro</span></span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="profile.php" class="text-slate-300 hover:text-white transition-colors">Retour au profil</a>
                    <a href="../auth/logout.php" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Updated main container with modern styling -->
    <div class="container max-w-3xl mx-auto px-6 py-8">

        <!-- Added header section with icon -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h1 class="text-4xl font-bold text-white">Modifier mon <span class="text-orange-500">profil</span></h1>
            </div>
            <p class="text-slate-400">Mettez à jour vos informations personnelles et professionnelles</p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 text-green-400 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Profil mis à jour avec succès !
            </div>
        <?php endif; ?>

        <!-- Updated form container with glassmorphism effects -->
        <form action="" method="POST" class="space-y-6 bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 p-8 rounded-xl">

            <!-- Photo URL -->
            <div>
                <label class="block mb-2 font-medium text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Photo de profil (URL)
                </label>
                <input 
                    type="url"
                    name="photo"
                    value="<?= htmlspecialchars($photo) ?>"
                    placeholder="https://example.com/photo.jpg"
                    class="w-full p-3 bg-slate-900/70 rounded-lg border border-slate-700 text-slate-200 placeholder-slate-500 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none"
                >
                <p class="text-sm text-slate-500 mt-2">
                    Collez l'URL d'une image (JPEG, PNG, etc.)
                </p>
            </div>

            <!-- Updated Bio field with improved styling -->
            <div>
                <label class="block mb-2 font-medium text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Biographie
                </label>
                <textarea 
                    rows="4"
                    name="biographie"
                    placeholder="Parlez-nous de votre parcours et de votre expertise..."
                    class="w-full p-3 bg-slate-900/70 rounded-lg border border-slate-700 text-slate-200 placeholder-slate-500 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none resize-none"
                ><?= htmlspecialchars($biographie) ?></textarea>
            </div>

            <!-- Updated Experience field with icon -->
            <div>
                <label class="block mb-2 font-medium text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Années d'expérience
                </label>
                <input 
                    type="number" 
                    min="0" 
                    value="<?= htmlspecialchars($experience) ?>"
                    name="experience"
                    placeholder="Ex: 5"
                    class="w-full p-3 bg-slate-900/70 rounded-lg border border-slate-700 text-slate-200 placeholder-slate-500 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none"
                >
            </div>

            <!-- Updated Niveau field with icon -->
            <div>
                <label class="block mb-2 font-medium text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Niveau
                </label>
                <select name="niveau" class="w-full p-3 bg-slate-900/70 rounded-lg border border-slate-700 text-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all outline-none">
                    <option>selecter un niveau</option>
                    <option <?= ($niveau === 'Debutant') ? 'selected' : '' ?>>Debutant</option>
                    <option <?= ($niveau === 'Intermediaire') ? 'selected' : '' ?>>Intermediaire</option>
                    <option <?= ($niveau === 'Professionnel') ? 'selected' : '' ?>>Professionnel</option>
                </select>
            </div>

            <!-- Updated Disciplines field with modern checkbox styling -->
            <div>
                <label class="block mb-3 font-medium text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Disciplines
                </label>
                <div class="flex flex-wrap gap-3">
                    <?php foreach($alldisciplines as $row): ?>
                        <?php $isChecked = in_array($row['id_discipline'], $currentDiscIds) ? 'checked' : ''; ?>
                        <label class="flex items-center gap-2 px-4 py-2 bg-slate-900/50 rounded-lg border border-slate-700 hover:border-orange-500 transition-colors cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="disciplines[]" 
                                value="<?= $row['id_discipline'] ?>"
                                <?= $isChecked ?>
                                class="w-4 h-4 text-orange-500 bg-slate-800 border-slate-600 rounded focus:ring-orange-500 focus:ring-2"
                            >
                            <span class="text-slate-300"><?= htmlspecialchars($row['nom_discipline']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Updated submit button with gradient and modern hover effects -->
            <button
                type="submit"
                name="submit"
                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 py-3 rounded-lg font-semibold text-white hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer les modifications
            </button>

        </form>
    </div>

</body>
</html>
