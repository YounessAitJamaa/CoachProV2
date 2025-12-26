<?php 

    require_once __DIR__ . '/../classes/Sportif.php';


    class SportifRepository {
        private PDO $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }


        public function register(Sportif $sportif) {
            try {
                $this->db->beginTransaction();

                $sql1 = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, date_inscription,  id_role)
                        VALUES (:nom, :prenom, :email, :mot_de_passe, :date_inscription, :role)";
                $stmt1 = $this->db->prepare($sql1);
                $stmt1->execute([
                    'nom' => $sportif->getNom(),
                    'prenom' => $sportif->getPrenom(),
                    'email' => $sportif->getEmail(),
                    'mot_de_passe' => password_hash($sportif->getMotDePass(), PASSWORD_DEFAULT),
                    'date_inscription' => $sportif->getDateInscription(),
                    'role' => 1
                ]);

                $userId = $this->db->lastInsertId();
                
                $sql2 = "INSERT INTO sportif (photo, telephone, date_naissance, id_user)
                        VALUES (:photo, :telephone, :date_naissance, :id_u)";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([
                    'photo' => $sportif->getPhoto(),
                    'telephone' => $sportif->getTelephone(),
                    'date_naissance' => $sportif->getDatNaissance(),
                    'id_u' => $userId
                ]);

                $this->db->commit();
                return true;

            }catch (PDOException $err) {
                $this->db->rollBack();
                return false;
            }
        }

        public function getSportifByUserId(int $userId) {
            $sql = "SELECT id_sportif, photo, telephone, date_naissance 
                    FROM sportif 
                    WHERE id_user = :uid 
                    LIMIT 1";
                    
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['uid' => $userId]);
                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error in getSportifByUserId: " . $e->getMessage());
                return false;
            }
        }
    }







?>