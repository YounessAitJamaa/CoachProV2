<?php

    class Discipline 
    {
        private int $id_discipline;
        private string $nom_discipline;


        public function __construct(
            int $id_discipline,
            string $nom_discipline
        )
        {
            $this->id_discipline = $id_discipline;
            $this->nom_discipline = $nom_discipline;
        }

        public function getIdDiscipline(): int { return $this->id_discipline; }
        public function getNomDiscipline(): string { return $this->nom_discipline; }
    }



?>