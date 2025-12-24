<?php

    require_once __DIR__ . '/User.php';

    class Coach extends User
    {
        private int $id_coach;
        private string $photo;
        private string $biographie;
        private int $experience;
        private string $niveau;
        private ?string $adresse;
        private ?string $telephone;


        public function __construct(
            int $id_user, 
            string $nom, 
            string $prenom, 
            string $email, 
            string $mot_de_pass, 
            string $date_inscription,
            int $id_coach,
            string $photo,  
            string $biographie,
            int $experience,
            string $niveau,
            ?string $adresse,
            ?string $telephone
        )
        {
            parent::__construct(
                $id_user, 
                $nom, 
                $prenom, 
                $email, 
                $mot_de_pass, 
                2, 
                $date_inscription
            );
            
            $this->id_coach = $id_coach;
            $this->photo = $photo;
            $this->biographie = $biographie;
            $this->experience = $experience;
            $this->niveau = $niveau;
            $this->adresse = $adresse;
            $this->telephone = $telephone;
        }


        public function getIdCoach(): int { return $this->id_coach; }
        public function getPhoto(): string { return $this->photo; }
        public function getBiographie(): string { return $this->biographie; }
        public function getExperience(): int { return $this->experience; }
        public function getNiveau(): string { return $this->niveau; }
        public function getAdresse(): string { return $this->adresse; }
        public function getTelephone(): string { return $this->telephone; }
    }

?>