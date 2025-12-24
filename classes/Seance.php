<?php

    class Seance 
    {
        private int $id_seance;
        private string $date_seance;
        private string $heure;
        private string $statut;
        private int $id_sportif;
        private int $id_coach;
        private int $id_discipline;

        public function __construct(
            int $id_seance, 
            string $date_seance,
            string $heure,
            string $statut, 
            int $id_sportif,
            int $id_coach,
            int $id_discipline
        ) {
            $this->id_seance = $id_seance;
            $this->date_seance = $date_seance;
            $this->heure = $heure;
            $this->statut = $statut;
            $this->id_sportif = $id_sportif;
            $this->id_coach = $id_coach;
            $this->id_discipline = $id_discipline;
        }

        public function getIdSeance(): int { return $this->id_seance; }
        public function getDateSeance(): string { return $this->date_seance; }
        public function getHeure(): string { return $this->heure; }
        public function getStatut(): string { return $this->statut; }
    }
?>