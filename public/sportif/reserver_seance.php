<?php

    session_start();
    require_once '../../config/database.php';
    require_once '../../repositories/CoachRepository.php';
    require_once '../../classes/Discipline.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $coachRepo = new CoachRepository($db);

    $selectedDiscipline = isset($_GET['discipline']) && $_GET['discipline'] !== '' ? (int)$_GET['discipline'] : null;

    $coaches = $coachRepo->getAvailableCoaches($selectedDiscipline);
    $disciplineList = $coachRepo->getAllDisciplines();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une séance - CoachPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <?php include '../../includes/header.php' ?>
    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <!-- Made page header responsive with adjusted text sizes -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">
                Réserver une <span class="text-orange-500">séance</span>
            </h1>
            <p class="text-sm sm:text-base text-slate-400">Choisissez votre coach, la date et l'heure qui vous conviennent</p>
        </div>

        <!-- Made layout responsive - filters stack above cards on mobile, side-by-side on desktop -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Sidebar - Filters -->
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 lg:sticky lg:top-8 space-y-4 sm:space-y-6">
                    <div class="flex items-center gap-3 mb-2 sm:mb-4">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <h2 class="text-base sm:text-lg font-bold text-white">Filtres</h2>
                    </div>
                    <form action="reserver_seance.php" method="GET" id="filterForm">
                        <!-- Discipline Filter -->
                        <div>
                            <label for="discipline" class="block text-sm font-medium text-slate-300 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Discipline
                            </label>
                            <select id="discipline" name="discipline" onchange="this.form.submit()" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-lg p-2.5 sm:p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-sm">
                                <option value="">Toutes les disciplines</option>
                                <?php foreach($disciplineList as $discipline): ?>
                                    <option value="<?= $discipline->getIdDiscipline() ?>" 
                                        <?= ($selectedDiscipline == $discipline->getIdDiscipline()) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($discipline->getNomDiscipline()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>   
                        </div>

                        <!-- Reset Filters Button -->
                        <a href="reserver_seance.php" class="block text-center mt-4 w-full py-2 px-4 bg-slate-900/50 border border-slate-700 text-slate-300 rounded-lg hover:bg-slate-700 hover:text-white transition-all duration-200 text-sm font-medium">
                            Réinitialiser
                        </a>
                    </form>
                </div>
                
            </div>

            <!-- Made coach cards responsive - single column on mobile, 2 columns on tablet+ -->
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Coach Card 1 -->
                    <?php if (empty($coaches)): ?>
                        <div class="col-span-full py-12 text-center bg-slate-800/30 rounded-xl border border-dashed border-slate-700">
                            <p class="text-slate-400">Aucun coach ne correspond à vos critères de recherche.</p>
                            <a href="reserver_seance.php" class="text-orange-500 hover:underline mt-2 inline-block">Voir tous les coachs</a>
                        </div>
                    <?php else: ?>
                  <?php foreach($coaches as $row): ?>  
                    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 hover:border-orange-500/50 transition-all duration-300">
                        <div class="flex items-start gap-3 sm:gap-4 mb-4">
                            <div class="relative flex-shrink-0">
                                <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Coach Photo" class="rounded-full w-16 h-16 sm:w-20 sm:h-20 border-4 border-slate-700">
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 sm:w-6 sm:h-6 bg-green-500 rounded-full border-2 sm:border-4 border-slate-800"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-lg sm:text-xl text-white mb-1 truncate"><?= htmlspecialchars($row['prenom']) .' '. htmlspecialchars($row['nom']) ?></h3>
                                <p class="text-orange-500 text-xs sm:text-sm font-medium mb-2">Coach de Tennis</p>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-slate-300 text-xs sm:text-sm"><?= htmlspecialchars($row['moyenne_note'] ?? 0); ?>/5 (<?= htmlspecialchars($row['total_avis']) ?> avis)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-slate-300 text-xs sm:text-sm">
                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?= htmlspecialchars($row['experience']) ?> ans d'expérience
                            </div>
                            <div class="flex items-center gap-2 text-slate-300 text-xs sm:text-sm">
                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Coach National
                            </div>
                        </div>

                        <a href="coach_detail.php?id=<?= htmlspecialchars($row['id_coach']) ?>" class="w-full bg-gradient-to-br from-orange-500 to-orange-600 hover:shadow-lg hover:shadow-orange-500/20 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition-all duration-300 hover:scale-[1.02] flex items-center justify-center gap-2 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Voir détails
                        </a>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>

</body>
</html>
