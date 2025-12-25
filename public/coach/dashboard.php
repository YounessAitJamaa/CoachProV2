<?php

    session_start();
    require_once '../../config/database.php';
    require_once '../../classes/Coach.php';
    require_once '../../repositories/SeanceRepository.php';


    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header('Location: ../login.php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $seanceRepo = new SeanceRepository($db);

    $coachId = $_SESSION['user_id'];

    $pendingDemands = $seanceRepo->getPendingDemandsCount($coachId);
    $todaySessions = $seanceRepo->getSessionCountForToday($coachId);
    $demands = $seanceRepo->getDetailPendingDemands($coachId);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Coach - CoachPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <?php  include '../../includes/header.php' ?>

    <!-- Updated main content to match sportif styling -->
    <div class="container mx-auto px-6 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">
                Bienvenue, <span class="text-orange-500">Coach</span> !
            </h1>
            <p class="text-slate-400 mb-6">Gérez vos séances et vos disponibilités facilement</p>
            <a href="disponibilites.php" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300 hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Gérer mes disponibilités
            </a>
            <a href="avis.php" class="inline-flex items-center gap-2 ml-4 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300 hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Voir mes avis
            </a>

            <a href="mes_seances.php" class="inline-flex items-center gap-2 ml-4 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300 hover:scale-105">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Historique des séances
            </a>

        </div>

        <!-- Updated stats cards to match sportif card design -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-lg"><?= $pendingDemands ?></h3>
                        <p class="text-slate-400 text-sm">Demandes en attente</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-lg"><?= $todaySessions ?></h3>
                        <p class="text-slate-400 text-sm">Séances aujourd'hui</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm">John Doe</h3>
                        <p class="text-slate-400 text-xs">17 Déc 2025, 15:00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated reservations section to match sportif styling -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h2 class="text-2xl font-bold text-white">Demandes de réservation</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($demands as $row): ?>
                    <!-- Reservation card 1 -->
                    <div class="bg-slate-900/50 rounded-lg p-6 border border-slate-700/30 hover:border-orange-500/50 transition-all duration-300">
                        <h3 class="font-bold text-xl mb-4 text-white"><?= htmlspecialchars($row['client_nom']) .' '. htmlspecialchars($row['client_prenom']) ?></h3>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="text-sm"><?= htmlspecialchars($row['nom_discipline']) ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm"><?= htmlspecialchars($row['date_seance']) ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm"><?= htmlspecialchars($row['heure']) ?></span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <form action="traiter_reservation.php" method="POST" class="flex-1">
                                <input type="hidden" name="id_seance" value="<?= $row['id_seance'] ?>">
                                <input type="hidden" name="action" value="accepte">
                                <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                    Accepter
                                </button>
                            </form>

                            <form action="traiter_reservation.php" method="POST" class="flex-1">
                                <input type="hidden" name="id_seance" value="<?= $row['id_seance'] ?>">
                                <input type="hidden" name="action" value="refuse">
                                <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors" 
                                        onclick="return confirm('Voulez-vous vraiment refuser cette demande ?')">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>