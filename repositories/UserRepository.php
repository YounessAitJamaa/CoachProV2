<?php

    require_once __DIR__ . '/../classes/Coach.php';
    require_once __DIR__ . '/../classes/Sportif.php';


    class UserRepository {

        private PDO $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function login(string $email, string $mot_de_pass) {
            $stmt = $this->db->prepare("SELECT * FROM Utilisateur WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData && password_verify($mot_de_pass, $userData['mot_de_passe'])) {

                if($userData['id_role'] == 2) {

                    return $this->getCoachDetails($userData);
                }else {
                    return $this->getSportifDetails($userData);
                }
            }   
            return false;

        }

        private function getCoachDetails(array $userData) {
            $stmt = $this->db->prepare("SELECT * FROM coach WHERE id_user = :id");
            $stmt->execute(['id' => $userData['id_user']]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);

            return new Coach( 
                $userData['nom'], 
                $userData['prenom'], 
                $userData['email'],
                $userData['mot_de_passe'], 
                $details['photo'],
                $details['biographie'], 
                $details['experience'],
                $details['niveau'],
                $details['adresse'], 
                $details['telephone'],
                $userData['id_user'],
                $details['id_coach'],
                $userData['date_inscription'], 
            );
        }

        public function getSportifDetails(array $userData) {
            $stmt = $this->db->prepare("SELECT * FROM sportif WHERE id_user = :id");
            $stmt->execute(['id' => $userData['id_user']]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);

            return new Sportif(
                $userData['nom'], 
                $userData['prenom'], 
                $userData['email'],
                $userData['mot_de_passe'], 
                $details['photo'], 
                $details['telephone'], 
                $details['date_naissance'],
                $userData['id_user'], 
                $details['id_sportif'],
                $userData['date_inscription']
            );
        }
    }



?>