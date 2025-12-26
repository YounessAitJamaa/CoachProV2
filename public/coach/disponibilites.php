<?php
    session_start();
    require_once '../../config/database.php';
    require_once '../../repositories/CoachRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header("Location: ../login.php");
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $coachRepo = new CoachRepository($db);


    $coachData = $coachRepo->getCoachByUserId($_SESSION['user_id']);
    $coachId = $coachData['id_coach'];

    if (isset($_POST['submit'])) {
        $date = $_POST['date'];
        $start = $_POST['heure_debut'];
        $end = $_POST['heure_fin'];

        if ($coachRepo->addAvailability($coachId, $date, $start, $end)) {
            header("Location: disponibilites.php?success=1");
            exit();
        }
    }

    $availabilities = $coachRepo->getCoachDisponibilites($coachId);
    $availList = $availabilities->fetchAll(PDO::FETCH_ASSOC);
?>








<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer mes disponibilités - CoachPro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <?php  include '../../includes/header.php' ?>

    <!-- Updated container with better spacing -->
    <div class="container mx-auto px-6 py-8">

        <!-- Updated page title section with icon and better styling -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h1 class="text-4xl font-bold text-white">Mes <span class="text-orange-500">disponibilités</span></h1>
            </div>
            <p class="text-slate-400 ml-11">
                Définissez vos créneaux disponibles pour les séances
            </p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 text-green-400 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Profil mis à jour avec succès !
            </div>
        <?php endif; ?>

        <!-- Updated add availability card with glassmorphism effects -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6 mb-8">
            <h2 class="text-xl font-semibold mb-6 flex items-center gap-2 text-white">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter une disponibilité
            </h2>

            <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Date -->
                <div>
                    <label class="block text-sm text-slate-300 mb-2 font-medium">Date</label>
                    <input
                        type="date"
                        name="date"
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-900/70 border border-slate-700/50 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none text-white transition-all"
                    >
                </div>

                <!-- Start Time -->
                <div>
                    <label class="block text-sm text-slate-300 mb-2 font-medium">Heure début</label>
                    <input
                        type="time"
                        name="heure_debut"
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-900/70 border border-slate-700/50 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none text-white transition-all"
                    >
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm text-slate-300 mb-2 font-medium">Heure fin</label>
                    <input
                        type="time"
                        name="heure_fin"
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-900/70 border border-slate-700/50 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none text-white transition-all"
                    >
                </div>

                <!-- Button -->
                <div class="flex items-end">
                    <button
                        type="submit"
                        name="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:shadow-lg hover:shadow-orange-500/20 text-white font-semibold py-2.5 rounded-lg transition-all duration-300 hover:scale-105"
                    >
                        Ajouter
                    </button>
                </div>
            </form>
        </div>

        <!-- Updated existing availability list with glassmorphism and better card design -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-6 flex items-center gap-2 text-white">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Créneaux existants
            </h2>

            <div class="space-y-4">

                <!-- Updated slot cards with better styling and hover effects -->
            <div class="space-y-4">
                <?php if (empty($availList)): ?>
                    <p class="text-slate-400 text-center py-8">Aucun créneau défini pour le moment.</p>
                <?php else: ?>
                    <?php foreach($availList as $row): ?>
                        <div class="flex items-center justify-between bg-slate-900/50 border border-slate-700/30 rounded-lg p-5 hover:border-orange-500/50 transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-white"><?= htmlspecialchars($row['date_disponibilite']) ?></p>
                                    <div class="flex items-center gap-2 text-slate-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($row['heure_debut']) ?> – <?= htmlspecialchars($row['heure_fin']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-1.5 bg-orange-500/20 border border-orange-500/30 text-orange-400 font-medium text-sm rounded-full">Disponible</span>
                                <a href="delete_disponibilite.php?id=<?= $row['id_disponibilite'] ?>" 
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette disponibilité ?')"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300 hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Annuler
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            </div>
        </div>

    </div>

</body>
</html>
