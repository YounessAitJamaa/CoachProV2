<?php 

    // require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../classes/Coach.php';
    require_once __DIR__ . '/../classes/Discipline.php';


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

        public function getAvailableCoaches(?int $disciplineId = null): array {
            $sql = "SELECT u.nom, u.prenom, c.*, 
                    AVG(a.note) as moyenne_note, 
                    COUNT(a.id_avis) as total_avis
                    FROM coach c
                    JOIN Utilisateur u ON c.id_user = u.id_user
                    LEFT JOIN avis a ON c.id_coach = a.id_coach";
            
            $params = [];
            if ($disciplineId) {
                $sql .= " JOIN coach_discipline cd ON c.id_coach = cd.id_coach 
                        WHERE cd.id_discipline = :discId";
                $params['discId'] = $disciplineId;
            }   

            $sql .= " GROUP BY c.id_coach, u.nom, u.prenom";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllDisciplines() {

            $sql = "SELECT id_discipline, nom_discipline FROM disciplines";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $disciplines = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $disciplines[] =  new Discipline(
                    (int)$row['id_discipline'],
                    $row['nom_discipline']
                );
            }

            return $disciplines;
        }

        public function getCoachById(int $id) {
            $sql = "SELECT 
                        u.nom AS coach_nom, 
                        u.prenom AS coach_prenom, 
                        c.experience, 
                        c.biographie, 
                        c.photo,
                        c.id_coach,
                        AVG(a.note) as moyenne_note, 
                        COUNT(a.id_avis) as total_avis
                    FROM coach c
                    JOIN Utilisateur u ON c.id_user = u.id_user
                    LEFT JOIN avis a ON c.id_coach = a.id_coach
                    WHERE c.id_coach = :id
                    GROUP BY c.id_coach, u.nom, u.prenom, c.experience, c.biographie, c.photo";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getDisciplinesByCoachId(int $coachId) {
            $sql = "SELECT d.nom_discipline 
            FROM disciplines d
            JOIN coach_discipline cd ON d.id_discipline = cd.id_discipline
            WHERE cd.id_coach = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $coachId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getCoachDisponibilites(int $coachId) {
            $sql = "SELECT * FROM disponibilite 
                    WHERE id_coach = :id 
                    AND statut = 'libre' 
                    ORDER BY date_disponibilite ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $coachId]);
            return $stmt;
        }

        public function getCoachByUserId(int $userId) {
            $sql = "SELECT id_coach FROM coach WHERE id_user = :uid";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['uid' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function addAvailability(int $coachId, string $date, string $start, string $end) {
            $sql = "INSERT INTO disponibilite (date_disponibilite, heure_debut, heure_fin, id_coach, statut) 
                    VALUES (:date, :start, :end, :cid, 'libre')";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'cid' => $coachId,
                'date' => $date,
                'start' => $start,
                'end' => $end
            ]);
        }

        public function deleteAvailability(int $dispoId, int $coachId) {
            $query = "DELETE FROM disponibilite
                    WHERE id_disponibilite = :dispoId 
                    AND id_coach = :coachId";
                    
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'dispoId' => $dispoId,
                'coachId' => $coachId
            ]);
        }
    }







?>