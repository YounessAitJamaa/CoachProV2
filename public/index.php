<?php
    
    
    session_start();
    require_once '../config/database.php'; 

    $page = $_GET['page'] ?? 'home';

    switch ($page) {
        case 'home':
            include 'home.php';
            break;
            
        case 'login': 
            include 'login.php';
            break;

        default:
            http_response_code(404);
            include '404.php';
            break;
    }
?>