<?php
    session_start();
    require_once '../../config/database.php';
    require_once '../../repositories/SeanceRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header("Location: ../login.php");
        exit();
    }   

    $database = new Database();
    $db = $database->getConnection();
    $seanceRepo = new SeanceRepository($db);

    
    $coachUserId = $_SESSION['user_id'];
    $seances = $seanceRepo->getSeancesByCoach($coachUserId);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Séances - CoachPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen text-slate-200">

    <?php include '../../includes/header.php' ?>

    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Historique des <span class="text-orange-500">Séances</span></h1>
                <p class="text-slate-400 mt-1">Consultez et gérez l'ensemble de vos réservations passées et futures.</p>
            </div>
            <a href="dashboard.php" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au Dashboard
            </a>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-700/30 text-slate-300 uppercase text-xs font-semibold tracking-wider">
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Discipline</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Heure</th>
                            <th class="px-6 py-4 text-right">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        <?php if (empty($seances)): ?>
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Aucune séance trouvée.</td></tr>
                        <?php else: ?>
                            <?php foreach($seances as $row): ?>
                                <tr class="hover:bg-slate-700/20 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-white"><?= htmlspecialchars($row['client_nom']) .' '. htmlspecialchars($row['client_prenom']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-500/10 text-blue-400 text-xs rounded-full border border-blue-500/20"><?= htmlspecialchars($row['nom_discipline'] ?? 'Non définie') ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-white"><?= date('d M Y', strtotime($row['date_seance'])) ?></td>
                                    <td class="px-6 py-4 text-white"><?= htmlspecialchars($row['heure']) ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <?php 
                                            $status = $row['statut'];
                                            $colorStatut = 'text-slate-400';
                                            $colorDot = 'bg-slate-400';

                                            if($status === 'accepte') {
                                                $colorStatut = 'text-green-500'; $colorDot = 'bg-green-500';
                                            } elseif ($status === 'en attente') {
                                                $colorStatut = 'text-orange-500'; $colorDot = 'bg-orange-500';
                                            } elseif ($status === 'annule') {
                                                $colorStatut = 'text-red-500'; $colorDot = 'bg-red-500';
                                            }
                                        ?>
                                        <span class="flex items-center justify-end gap-1.5 <?= $colorStatut ?> text-sm font-medium">
                                            <span class="w-2 h-2 rounded-full <?= $colorDot ?> animate-pulse"></span>
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        </div>

</body>
</html>