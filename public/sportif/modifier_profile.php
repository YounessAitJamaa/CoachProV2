<?php


    session_start();
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../classes/Sportif.php';
    require_once __DIR__ . '/../../repositories/UserRepository.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
        header('Location: ../login.php');
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();

    $userRepo = new UserRepository($db);

    $userId = $_SESSION['user_id'];

    $userData = $userRepo->getUserById($userId);
    $sportif = $userRepo->getSportifDetails($userData);

    $nom = $sportif->getNom();
    $prenom = $sportif->getPrenom();
    $email = $sportif->getEmail();
    $photo = $sportif->getPhoto();
    $telephone = $sportif->getTelephone();


    if(isset($_POST['submit'])) {
        $updatedNom = $_POST['nom'];
        $updatedPrenom = $_POST['prenom'];
        $updatedPhoto = $_POST['photo'];
        $updatedEmail = $_POST['email'];
        $updatedTel = $_POST['telephone'];


        $sportif->setNom($updatedNom);
        $sportif->setPrenom($updatedPrenom);
        $sportif->setEmail($updatedEmail);
        $sportif->setPhoto($updatedPhoto);
        $sportif->setTelephone($updatedTel);


        if($userRepo->updateSportif($sportif)) {
            $_SESSION['user_nom'] = $updatedNom;
            header('Location: modifier_profile.php?msg=success');
            exit();
        }

    }
?>







<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

<!-- Added navigation header to match coachProfile design -->
<nav class="bg-slate-900/50 backdrop-blur-sm border-b border-slate-700/50">
    <div class="container mx-auto px-4 md:px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-xl md:text-2xl font-bold text-white">Coach<span class="text-orange-500">Pro</span></span>
            </div>
            <a href="profile.php" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Retour</span>
            </a>
        </div>
    </div>
</nav>

<div class="max-w-4xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">

    <!-- Updated header with glassmorphism and icon -->
    <div class="flex items-center gap-3 mb-6 md:mb-8">
        <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-white">Modifier mon profil</h1>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 text-green-400 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Profil mis à jour avec succès !
        </div>
    <?php endif; ?>

    <!-- Updated form card with glassmorphism design and responsive layout -->
    <form action="" method="POST" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-4 md:p-8 space-y-6 hover:border-orange-500/50 transition-all duration-300">

        <!-- Profile Image URL -->
        <div>
            <label class="block text-sm text-slate-400 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                URL de la photo de profil
            </label>
            <input
                type="text"
                name="photo"
                placeholder="https://example.com/photo.jpg"
                value="<?= htmlspecialchars($photo) ?>"
                class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all text-white"
            >
            <p class="text-xs text-slate-500 mt-2 ml-1">
                Lien vers une image publique (jpg, png)
            </p>
        </div>

        <!-- Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div>
                <label class="block text-sm text-slate-400 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Nom
                </label>
                <input
                    type="text"
                    name="nom"
                    value="<?= htmlspecialchars($nom) ?>"
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all text-white"
                >
            </div>

            <div>
                <label class="block text-sm text-slate-400 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Prénom
                </label>
                <input
                    type="text"
                    name="prenom"
                    value="<?= htmlspecialchars($prenom) ?>"
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all text-white"
                >
            </div>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm text-slate-400 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Email
            </label>
            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
                class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all text-white"
            >
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-sm text-slate-400 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Téléphone
            </label>
            <input
                type="text"
                name="telephone"
                placeholder="+212 6 12 34 56 78"
                value="<?= htmlspecialchars($telephone) ?>"
                class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all text-white"
            >
        </div>

        <!-- Updated buttons with responsive layout and improved styling -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 pt-4">
            <a href="profile.php"
               class="w-full sm:w-auto px-6 py-3 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-700 hover:border-slate-500 transition-all duration-300 text-center">
                Annuler
            </a>

            <button
                type="submit"
                name="submit"
                class="w-full sm:w-auto px-6 py-3 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white font-semibold transition-all duration-300 shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40">
                Enregistrer
            </button>
        </div>

    </form>

</div>

</body>
</html>
