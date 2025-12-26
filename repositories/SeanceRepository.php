<?php

    class SeanceRepository {
        private PDO $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function getPendingDemandsCount(int $coachUserId): int {
            $stmt = $this->db->prepare("SELECT COUNT(*) 
                                        FROM seance 
                                        WHERE id_coach = (SELECT id_coach FROM coach WHERE id_user = :id) 
                                        AND statut = 'en attente'"
                                    );
            $stmt->execute(['id' => $coachUserId]);
            return $stmt->fetchColumn();
        }

        public function getSessionCountForToday(int $coachUserId): int {
            $stmt = $this->db->prepare("SELECT COUNT(*)
                                        FROM seance
                                        WHERE id_coach = (SELECT id_coach FROM coach WHERE id_user = :id) 
                                        AND date_seance = CURDATE() 
                                        AND statut = 'accepte'
                                    ");
            $stmt->execute(['id' => $coachUserId]);
            return $stmt->fetchColumn();
        }

        public function getDetailPendingDemands(int $coachUserId): array {
            $sql = "SELECT 
                        s.id_seance, 
                        s.date_seance, 
                        s.heure, 
                        s.statut, 
                        u.nom as client_nom, 
                        u.prenom as client_prenom, 
                        d.nom_discipline
                    FROM seance s
                    JOIN sportif sp ON s.id_sportif = sp.id_sportif
                    JOIN Utilisateur u ON sp.id_user = u.id_user
                    LEFT JOIN disciplines d ON s.id_discipline = d.id_discipline
                    JOIN coach c ON s.id_coach = c.id_coach
                    WHERE c.id_user = :id 
                    AND s.statut = 'en attente'
                    ORDER BY s.date_seance ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $coachUserId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getSportifSessionsCount(int $sportifId, string $statut): int {
            $sql = "SELECT COUNT(*)
                    FROM seance s
                    JOIN sportif sp ON s.id_sportif = sp.id_sportif
                    WHERE sp.id_user = :id AND s.statut = :statut
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id' => $sportifId,
                'statut' => $statut
            ]);
            return $stmt->fetchColumn();
        }

        public function getSportifSessionsCompletesCount(int $sportifId): int {
            $sql = "SELECT COUNT(s.id_seance) AS total
                    FROM seance s
                    JOIN sportif sp ON s.id_sportif = sp.id_sportif
                    WHERE sp.id_user = :id AND s.date_seance < CURDATE()
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $sportifId]);
            return $stmt->fetchColumn();
        }

        public function getSportifReservations(int $sportifId): array {
            $sql = "SELECT s.*, u.nom AS coach_nom, u.prenom AS coach_prenom 
                    FROM seance s
                    JOIN coach c ON s.id_coach = c.id_coach
                    JOIN Utilisateur u ON c.id_user = u.id_user
                    JOIN sportif sp ON s.id_sportif = sp.id_sportif
                    WHERE sp.id_user = :id
                    ORDER BY s.date_seance ASC
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $sportifId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getSeancesByCoach(int $coachUserId): array {
            $sql = "SELECT 
                        s.id_seance, 
                        s.date_seance, 
                        s.heure, 
                        s.statut, 
                        u.nom as client_nom, 
                        u.prenom as client_prenom, 
                        d.nom_discipline
                    FROM seance s
                    JOIN sportif sp ON s.id_sportif = sp.id_sportif
                    JOIN Utilisateur u ON sp.id_user = u.id_user
                    LEFT JOIN disciplines d ON s.id_discipline = d.id_discipline
                    JOIN coach c ON s.id_coach = c.id_coach
                    WHERE c.id_user = :id 
                    ORDER BY s.date_seance DESC, s.heure DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $coachUserId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function getAvailabilityDetail($disp_id) {
            $sql = "SELECT d.*, c.id_coach, u.nom, u.prenom, cd.id_discipline
                    FROM disponibilite d 
                    JOIN coach c ON d.id_coach = c.id_coach 
                    JOIN Utilisateur u ON c.id_user = u.id_user 
                    LEFT JOIN coach_discipline cd ON c.id_coach = cd.id_coach
                    WHERE d.id_disponibilite = :id 
                    AND d.statut = 'libre' 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $disp_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createBooking($id_sportif, $dispo) {
            try {
                $this->db->beginTransaction();

                
                $sqlSeance = "INSERT INTO seance (date_seance, heure, statut, id_sportif, id_coach, id_discipline, id_disponibilite) 
                            VALUES (:date, :heure, 'en attente', :id_s, :id_c, :id_disc, :id_d)";
                
                $stmtS = $this->db->prepare($sqlSeance);
                $stmtS->execute([
                    'date'    => $dispo['date_disponibilite'],
                    'heure'   => $dispo['heure_debut'],
                    'id_s'    => $id_sportif,
                    'id_c'    => $dispo['id_coach'],
                    'id_disc' => $dispo['id_discipline'] ?? 1,
                    'id_d'    => $dispo['id_disponibilite']
                ]);

                
                $sqlUpd = "UPDATE disponibilite SET statut = 'reserve' WHERE id_disponibilite = :id";
                $stmtU = $this->db->prepare($sqlUpd);
                $stmtU->execute(['id' => $dispo['id_disponibilite']]);

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log("Erreur réservation : " . $e->getMessage());
                return false;
            }
        }


        public function updateStatus(int $id_seance, string $status): bool {
            $sql = "UPDATE seance SET statut = :status WHERE id_seance = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['status' => $status, 'id' => $id_seance]);
        }

    
        public function rejectAndReleaseSlot(int $id_seance): bool {
            try {
                $this->db->beginTransaction();

                
                $sqlGet = "SELECT id_disponibilite FROM seance WHERE id_seance = :id";
                $stmtGet = $this->db->prepare($sqlGet);
                $stmtGet->execute(['id' => $id_seance]);
                $row = $stmtGet->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    
                    $sqlDisp = "UPDATE disponibilite SET statut = 'libre' WHERE id_disponibilite = :id_d";
                    $stmtDisp = $this->db->prepare($sqlDisp);
                    $stmtDisp->execute(['id_d' => $row['id_disponibilite']]);
                }

            
                $sqlSeance = "UPDATE seance SET statut = 'annule' WHERE id_seance = :id";
                $stmtS = $this->db->prepare($sqlSeance);
                $stmtS->execute(['id' => $id_seance]);

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log("Erreur lors du refus de séance : " . $e->getMessage());
                return false;
            }
        }
    }





?>