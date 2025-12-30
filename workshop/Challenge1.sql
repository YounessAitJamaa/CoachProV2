/* =====================================================
   nombre total de séances créées
===================================================== */

SELECT u.nom, u.prenom, COUNT(s.id) AS total
FROM seances s
JOIN coachs c ON s.coach_id = c.user_id
JOIN users u ON c.user_id = u.id
GROUP BY u.nom, u.prenom

/* =====================================================
   nombre de séances réservées
===================================================== */

SELECT u.nom, u.prenom, COUNT(s.id) AS total
FROM seances s
JOIN coachs c ON s.coach_id = c.user_id
JOIN users u ON c.user_id = u.id
WHERE s.statut = 'reservee'
GROUP BY u.nom, u.prenom

/* =====================================================
   taux de réservation (%)
===================================================== */

   SELECT u.nom, u.prenom, (SUM(s.statut = 'reservee') / COUNT(*) * 100) AS total
   FROM seances s
   JOIN coachs c ON s.coach_id = c.user_id
   JOIN users u ON c.user_id = u.id
   GROUP BY u.nom, u.prenom



/* =====================================================
   seulement les coachs ayant ≥3 séances
===================================================== */

SELECT u.nom, u.prenom, COUNT(s.id) AS total
FROM seances s
JOIN coachs c ON s.coach_id = c.user_id
JOIN users u ON c.user_id = u.id
GROUP BY u.nom, u.prenom
HAVING COUNT(*) >= 3