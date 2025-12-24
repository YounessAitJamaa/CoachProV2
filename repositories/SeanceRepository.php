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
    }





?>