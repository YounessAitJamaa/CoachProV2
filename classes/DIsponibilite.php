<?php

    class Disponibilite
    {
        private int $id_disponibilite;
        private string $date_disponibilite;
        private string $heure_debut;
        private string $heure_fin;
        private int $id_coach;
        private string $statut;


        public function __construct(
            int $id_disponibilite,
            string $date_disponibilite,
            string $heure_debut,
            string $statut
        )
        {
            
        }
    }

?>