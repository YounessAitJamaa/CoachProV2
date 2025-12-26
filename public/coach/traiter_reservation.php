<?php
    session_start();
    require_once '../../config/database.php';
    require_once '../../repositories/SeanceRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
        header("Location: ../login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_seance'], $_POST['action'])) {
        $database = new Database();
        $db = $database->getConnection();
        $seanceRepo = new SeanceRepository($db);

        $id_seance = intval($_POST['id_seance']);
        $action = $_POST['action']; 

        if ($action === 'accepte') {
            
            $seanceRepo->updateStatus($id_seance, 'accepte');
        } 
        elseif ($action === 'refuse') {
            
            $seanceRepo->rejectAndReleaseSlot($id_seance);
        }

        
        header("Location: dashboard.php?status=updated");
        exit();
    } else {
        header("Location: dashboard.php");
        exit();
    }