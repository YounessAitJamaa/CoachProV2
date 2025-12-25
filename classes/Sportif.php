<?php

    require_once 'User.php';

    class Sportif extends User 
    {
        private int $id_sportif;
        private string $photo;
        private string $telephone;
        private string $date_naissance;


        public function __construct( 
            string $nom, 
            string $prenom, 
            string $email, 
            string $mot_de_pass,
            string $photo = '../../assets/img/default.jpeg',
            string $telephone = '0000000000',
            string $date_naissance = '1990-01-01',  
            int $id_user = 0,
            int $id_sportif = 0,
            string $date_inscription = ''
        ){
            if(empty($date_inscription)) $date_inscription = date('Y-m-d');
            parent::__construct(
                $id_user, 
                $nom, 
                $prenom, 
                $email, 
                $mot_de_pass, 
                1, 
                $date_inscription
            );

            $this->id_sportif = $id_sportif;
            $this->photo = $photo;
            $this->telephone = $telephone;
            $this->date_naissance = $date_naissance;
        }

        public function getIdSportif(): int { return $this->id_sportif; }
        public function getPhoto(): string { return $this->photo; }
        public function getTelephone(): string { return $this->telephone; }
        public function getDatNaissance(): string { return $this->date_naissance; }

        public function setPhoto(string $photo) { $this->photo = $photo;} 
        public function setTelephone(string $telephone) { $this->telephone = $telephone; }

    }

?>