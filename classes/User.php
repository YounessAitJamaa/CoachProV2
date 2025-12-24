<?php

    abstract class User 
    {

        protected int $id_user;
        protected string $nom;
        protected string $prenom;
        protected string $email;
        protected string $mot_de_pass;
        protected int $id_role;
        protected string $date_inscription;

        public function __construct(int $id_user, string $nom, string $prenom, string $email, string $mot_de_pass, int $id_role, string $date_inscription) 
        {
            $this->id_user = $id_user;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->mot_de_pass = $mot_de_pass;
            $this->id_role = $id_role;
            $this->date_inscription = $date_inscription;
        }

        public function getId(): int { return $this->id_user;} 
        public function getNom(): string { return $this->nom;}
        public function getEmail(): string { return $this->email;}
        public function getPrenom(): string { return $this->prenom;}
        public function getMotDePass(): string { return $this->mot_de_pass;}
        public function getIdRole(): int { return $this->id_role;}
        public function getDateInscription(): string { return $this->date_inscription;}
    }

?>