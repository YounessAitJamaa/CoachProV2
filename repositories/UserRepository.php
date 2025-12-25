<?php

    require_once __DIR__ . '../../classes/Coach.php';
    require_once __DIR__ .  '../../classes/Sportif.php';


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

        public function getCoachDetails(array $userData) {
            $stmt = $this->db->prepare("SELECT * FROM coach WHERE id_user = :id");
            $stmt->execute(['id' => $userData['id_user']]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);

            return new Coach( 
                (int)$userData['id_user'],     
                $userData['nom'],                 
                $userData['prenom'],              
                $userData['email'],               
                $userData['mot_de_passe'],        
                $userData['date_inscription'],    
                (int)$details['id_coach'],        
                $details['photo'],                
                $details['biographie'],           
                (int)$details['experience'],    
                $details['niveau'],               
                $details['adresse'],              
                $details['telephone']
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
                (int)$userData['id_user'], 
                (int)$details['id_sportif'],
                $userData['date_inscription']
            );
        }


        public function getUserById(int $userId) {
            $stmt = $this->db->prepare("SELECT * FROM Utilisateur WHERE id_user = :id");
            $stmt->execute(['id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }


        public function updateSportif(Sportif $sportif) {
            try {
                $this->db->beginTransaction();

                $sql1 = "UPDATE Utilisateur SET nom = :nom, prenom = :prenom, email = :email
                            WHERE id_user = :id";
                $stmt1 = $this->db->prepare($sql1);
                $stmt1->execute([
                    'nom' => $sportif->getNom(),
                    'prenom' => $sportif->getPrenom(),
                    'email' => $sportif->getEmail(),
                    'id' => $sportif->getId()
                ]);

                $sql2 = "UPDATE sportif SET photo = :photo, telephone = :telephone
                        WHERE id_user = :id";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([
                    'photo' => $sportif->getPhoto(),
                    'telephone' => $sportif->getTelephone(),
                    'id' => $sportif->getId()
                ]);

                $this->db->commit();
                return true;
            } catch(PDOException $err) {
                $this->db->rollBack();
                return false;
            }
        }

        public function getCoachDisciplinesIds(int $idCoach) {
            $sql = "SELECT d.id_discipline 
                    FROM disciplines d
                    JOIN coach_discipline cd ON d.id_discipline = cd.id_discipline
                    WHERE cd.id_coach = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idCoach]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getCoachDisciplines(int $idCoach) {
            $sql = "SELECT d.nom_discipline 
                    FROM disciplines d
                    JOIN coach_discipline cd ON d.id_discipline = cd.id_discipline
                    WHERE cd.id_coach = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idCoach]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getAllDisciplines() {
            $stmt = $this->db->prepare("SELECT * FROM disciplines");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        public function updateCoach(Coach $coach, array $disciplines) {
            try {
                $this->db->beginTransaction();

                $sql1 = "UPDATE coach SET photo = :photo, biographie = :biographie, experience = :experience, niveau = :niveau 
                            WHERE id_user = :id";
                $stmt1 = $this->db->prepare($sql1);
                $stmt1->execute([
                    'photo' => $coach->getPhoto(),
                    'biographie' => $coach->getBiographie(),
                    'experience' => $coach->getExperience(),
                    'niveau' => $coach->getNiveau(),
                    'id' => $coach->getId()
                ]);

                $stmDel = $this->db->prepare("DELETE FROM coach_discipline WHERE id_coach = :id");
                $stmDel->execute(['id' => $coach->getIdCoach()]);
                
                $stmIns = $this->db->prepare("INSERT INTO coach_discipline (id_coach, id_discipline)
                                                VALUES (:id_c, :id_d)
                                            ");
                foreach ($disciplines as $d) {
                    $stmIns->execute([
                        'id_c' => $coach->getIdCoach(),
                        'id_d' => $d
                    ]);
                }   
                
                $this->db->commit();
                return true;
    
            }catch(PDOException $e) {
                $this->db->rollBack();
                return false;
            }
        }
    }



?>