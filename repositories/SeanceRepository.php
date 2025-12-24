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
                                        AND statut = 'en attent'"
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
            $sql = "SELECT s.*, u.nom as client_nom, u.prenom as client_prenom, d.nom_discipline
                    FROM seance s
                    JOIN sportif sp ON s.id_sportif = sp.id_sportif
                    JOIN Utilisateur u ON sp.id_user = u.id_user
                    JOIN disciplines d ON s.id_discipline = d.id_discipline
                    WHERE s.id_coach = (SELECT id_coach FROM coach WHERE id_user = :id)
                    AND s.statut = 'en attente'";
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
    }





?>