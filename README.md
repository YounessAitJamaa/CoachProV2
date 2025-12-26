# 🏅 CoachPro : Plateforme de Mise en Relation Sportive

**CoachPro** est une application web dynamique conçue pour connecter les athlètes et passionnés de sport avec des coachs professionnels certifiés (Football, Tennis, Natation, Préparation physique, etc.). 

La plateforme offre une expérience fluide permettant aux sportifs de réserver des séances sur mesure et aux coachs de gérer leur activité professionnelle avec des outils de suivi avancés.



---

## 🚀 Fonctionnalités Principales

### 👤 Espace Sportif (Clients)
- **Exploration :** Consultation des profils détaillés des coachs (disciplines, diplômes, expérience).
- **Réservation en ligne :** Système de réservation basé sur les créneaux réels du coach.
- **Gestion du planning :** Dashboard pour consulter l'historique, modifier ou annuler des réservations.
- **Feedback :** Système d'avis et de notation après chaque séance.

### 👟 Espace Coach (Professionnels)
- **Tableau de bord (Dashboard) :** Statistiques en temps réel (demandes en attente, séances du jour et du lendemain).
- **Gestion des disponibilités :** Calendrier interactif pour mettre à jour les créneaux horaires.
- **Gestion des séances :** Système de validation (Accepter/Refuser) des demandes entrantes.
- **Profil Professionnel :** Personnalisation complète (bio, photo, certifications).

---

## 🛠️ Stack Technique

- **Backend :** PHP 8.x (Architecture modulaire et sécurisée)
- **Base de données :** MySQL (Modèle relationnel optimisé)
- **Frontend :** HTML5, Tailwind CSS (Design moderne et responsive)
- **Interactivité :** JavaScript ES6+, SweetAlert2 (Modals et confirmations)
- **Sécurité :** Requêtes préparées, Hashage Bcrypt



---

## 🛡️ Sécurité & Performance

Le projet intègre des protocoles de sécurité rigoureux pour protéger les utilisateurs :
- **Prévention des Injections SQL :** Utilisation systématique de requêtes préparées (`mysqli_prepare`).
- **Protection XSS :** Nettoyage et échappement des données via `htmlspecialchars()`.
- **Hashage de Mots de Passe :** Utilisation de l'algorithme `bcrypt` (via `password_hash`).
- **Gestion des Rôles :** Redirection automatique et contrôle d'accès strict par session.
- **Transactions SQL :** Sécurisation des annulations pour garantir la cohérence entre les séances et les disponibilités.

---

## 📱 Design & UX

- **Responsive Design :** Interface optimisée pour mobile, tablette et desktop.
- **UX Fluide :** Utilisation de SweetAlert pour des notifications non intrusives et des confirmations d'actions claires.
- **Validation Temps Réel :** Formulaires sécurisés par Regex pour l'email, le téléphone et les mots de passe.

---

## 🎁 Fonctionnalités Bonus

- ⭐ **Système d'avis :** Collecte et affichage des notes coachs.
- 📄 **Génération PDF :** Rapports de statistiques et historiques de séances.
- 📧 **Notifications Mail :** Confirmation et modification de réservation.
- 🚫 **Page 404 :** Erreur personnalisée pour une navigation sans rupture.

---

## ⚙️ Installation Rapide

1. **Clonage du projet**
   ```bash
   git clone [https://github.com/votre-compte/coachpro.git](https://github.com/votre-compte/coachpro.git)