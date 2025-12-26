<?php
    session_start();
    require_once '../../config/database.php';
    require_once '../../repositories/CoachRepository.php';

    
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header('Location: ../login.php');
        exit();
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $database = new Database();
        $db = $database->getConnection();
        $coachRepo = new CoachRepository($db);

        $coachData = $coachRepo->getCoachByUserId($_SESSION['user_id']);
        $coachId = $coachData['id_coach'];
        $dispoId = (int)$_GET['id'];

        if ($coachRepo->deleteAvailability($dispoId, $coachId)) {
            header('Location: disponibilites.php?deleted=1');
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
    } else {
        header('Location: disponibilites.php');
        exit();
    }