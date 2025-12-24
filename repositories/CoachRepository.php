<?php 

    // require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../classes/Coach.php';


    class CoachRepository {
        private PDO $db;


        public function __construct(PDO $db) {
            $this->db = $db;
        }


        public function register(Coach $coach) {
            try {
                $this->db->beginTransaction();

                $sql1 = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, date_inscription,  id_role)
                        VALUES (:nom, :prenom, :email, :mot_de_passe, :date_inscription, :role)";
                $stmt1 = $this->db->prepare($sql1);
                $stmt1->execute([
                    'nom' => $coach->getNom(),
                    'prenom' => $coach->getPrenom(),
                    'email' => $coach->getEmail(),
                    'mot_de_passe' => password_hash($coach->getMotDePass(), PASSWORD_DEFAULT),
                    'date_inscription' => $coach->getDateInscription(),
                    'role' => 2
                ]);

                $userId = $this->db->lastInsertId();
                
                $sql2 = "INSERT INTO coach (photo, biographie, experience, niveau, adresse, telephone, id_user)
                        VALUES (:photo, :biographie, :experience, :niveau, :adresse, :telephone, :id_u)";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([
                    'photo' => $coach->getPhoto(),
                    'biographie' => $coach->getBiographie(),
                    'experience' => $coach->getExperience(),
                    'niveau' => $coach->getNiveau(),
                    'adresse' => $coach->getAdresse(),
                    'telephone' => $coach->getTelephone(),
                    'id_u' => $userId
                ]);

                $this->db->commit();
                return true;

            }catch (PDOException $err) {
                $this->db->rollBack();
                return false;
            }
        }
    }







?>