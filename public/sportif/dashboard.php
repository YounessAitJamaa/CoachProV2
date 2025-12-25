<?php

    session_start();
    require_once '../../config/database.php';
    require_once '../../classes/Sportif.php';
    require_once '../../repositories/SeanceRepository.php';
    
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
        header('Location: ../login.php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $seanceRepo = new SeanceRepository($db);

    $sportifId = $_SESSION['user_id'];
    $nom = $_SESSION['user_nom'];


    $totalProgmmes = $seanceRepo->getSportifSessionsCount($sportifId, 'accepte');
    $totalCompletes = $seanceRepo->getSportifSessionsCompletesCount($sportifId, 'terminee');
    $reservations = $seanceRepo->getSportifReservations($sportifId);

    



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sportif - CoachPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <?php include '../../includes/header.php' ?>

    <!-- Made dashboard content responsive with better mobile spacing -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8 lg:py-10">
        <!-- Made welcome section responsive with adjusted text sizes -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2 leading-tight">
                Bienvenue, <span class="text-orange-500"><?= htmlspecialchars($nom) ?></span> !
            </h1>
            <p class="text-slate-400 text-sm sm:text-base">Gérez vos séances et réservations en toute simplicité</p>
        </div>

        <!-- Made quick actions grid fully responsive: stacked on mobile, 2 cols on tablet, 3 cols on desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6 mb-6 sm:mb-8">
            <a href="reserver_seance.php" class="group bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-5 sm:p-6 hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300 hover:scale-105 active:scale-95">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-base sm:text-lg">Réserver une séance</h3>
                        <p class="text-orange-100 text-xs sm:text-sm mt-0.5">Trouvez votre coach</p>
                    </div>
                </div>
            </a>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-xl sm:text-2xl"><?= $totalProgmmes ?></h3>
                        <p class="text-slate-400 text-xs sm:text-sm mt-0.5">Séances programmées</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-5 sm:p-6 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-xl sm:text-2xl"><?= htmlspecialchars($totalCompletes) ?></h3>
                        <p class="text-slate-400 text-xs sm:text-sm mt-0.5">Séances complétées</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($_GET['cancelled'])): ?>
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-3 text-green-400 animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">Votre séance a été annulée et le créneau a été libéré.</span>
            </div>
        <?php endif; ?>

        <!-- Made reservations section responsive -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-6 lg:p-8">
            <div class="flex items-center gap-2 sm:gap-3 mb-5 sm:mb-6">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h2 class="text-xl sm:text-2xl font-bold text-white">Mes réservations</h2>
            </div>
            <?php if ($reservations): ?>
                <?php foreach($reservations as $row): ?>
                    <!-- Made reservation card fully responsive with stacked layout on mobile -->
                    <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 sm:p-5 hover:border-orange-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/10">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6">
                            <div class="flex items-start gap-3 sm:gap-4 flex-1 min-w-0">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-semibold text-base sm:text-lg mb-1">Coach <?= htmlspecialchars($row['coach_nom']) ?></p>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                        <span class="text-xs sm:text-sm text-slate-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <?= htmlspecialchars($row['date_seance']) ?>
                                        </span>
                                        <span class="text-xs sm:text-sm text-slate-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <?= htmlspecialchars($row['heure']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Made action buttons responsive: stacked on mobile, horizontal on desktop -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 lg:flex-shrink-0">
                                
                                <span class="px-4 py-2.5 text-xs sm:text-sm font-medium rounded-lg 
                                    <?= $row['statut'] === 'accepte' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' ?> 
                                    text-center">
                                    <?= htmlspecialchars(ucfirst($row['statut'])) ?>
                                </span>

                                <?php if($row['statut'] === 'en attente'): ?>
                                    <form method="POST" class="cancel-seance-form">
                                        <input type="hidden" name="seance_id" value="<?= $row['id_seance'] ?>">
                                        <button type="submit" name="cancel_seance" class="w-full px-4 py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg border border-red-500/20 hover:border-red-500/40 transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span class="text-xs sm:text-sm font-medium">Annuler</span>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-slate-500 text-xs italic px-2">Confirmation verrouillée</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-20 h-20 bg-slate-700/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Aucune séance prévue</h3>
                    <p class="text-slate-400 mt-1 mb-6 max-w-xs">Vous n'avez pas encore de réservations. C'est le moment idéal pour commencer !</p>
                    <a href="reserver_seance.php" class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                        Réserver ma première séance
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>