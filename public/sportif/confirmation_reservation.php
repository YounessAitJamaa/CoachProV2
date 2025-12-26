<?php
    session_start();
    require_once '../../config/database.php';
    require_once '../../repositories/SeanceRepository.php';
    require_once '../../repositories/SportifRepository.php';

    
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
        header('Location: ../login.php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $seanceRepo = new SeanceRepository($db);
    $sportifRepo = new SportifRepository($db);

    $disp_id = $_GET['disp'] ?? null;
    if (!$disp_id) {
        header('Location: reserver_seance.php');
        exit();
    }

    $sportif = $sportifRepo->getSportifByUserId($_SESSION['user_id']);
    $id_sportif = $sportif['id_sportif'];

    $dispo = $seanceRepo->getAvailabilityDetail($disp_id);
    if (!$dispo) {
        header('Location: reserver_seance.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        if ($seanceRepo->createBooking($id_sportif, $dispo)) {
            header('Location: reserver_seance.php?success=1');
            exit();
        } else {
            $error = "Une erreur est survenue lors de la réservation.";
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation réservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">

<div class="max-w-lg w-full bg-slate-800/50 border border-slate-700 rounded-xl p-6">

    <h1 class="text-2xl font-bold mb-6 text-center">
        Confirmation de réservation
    </h1>

    <div class="space-y-4 mb-6">
        <div class="flex justify-between">
            <span class="text-slate-400">Coach</span>
            <span class="font-semibold"><?= htmlspecialchars($dispo['prenom'].' '.$dispo['nom']) ?></span>
        </div>

        <div class="flex justify-between">
            <span class="text-slate-400">Date</span>
            <span class="font-semibold">
                <?= date('Y M d', strtotime($dispo['date_disponibilite'])) ?>
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-slate-400">Heure</span>
            <span class="font-semibold text-orange-500">
                <?= date('H:i', strtotime($dispo['heure_debut'])) ?> 
                → 
                <?= date('H:i', strtotime($dispo['heure_fin'])) ?>
            </span>
        </div>
    </div>

    <form method="POST" class="flex gap-4">
        <input type="hidden" name="disp_id" value="<?= $disp_id ?>">

        <a href="coach_detail.php?id=<?= $dispo['id_coach'] ?>"
           class="flex-1 text-center border border-slate-600 rounded-lg py-2 hover:bg-slate-700">
            Annuler
        </a>

        <button type="submit"
                name="submit"
                class="flex-1 bg-orange-500 hover:bg-orange-400 text-slate-900 font-semibold rounded-lg py-2">
            Confirmer
        </button>
    </form>

</div>

</body>
</html>