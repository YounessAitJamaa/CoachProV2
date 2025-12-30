/* =====================================================
    qui réservent toujours moins de 24h avant la séance
===================================================== */



SELECT 
    u.nom,
    u.prenom,
    s.user_id AS sportif_id
FROM sportifs s
JOIN users u ON u.id = s.user_id
JOIN reservations r ON r.sportif_id = s.user_id
JOIN seances se ON se.id = r.seance_id
GROUP BY s.user_id, u.nom, u.prenom
HAVING MIN(TIMESTAMPDIFF(HOUR, r.reserved_at, 
                         TIMESTAMP(se.date_seance, se.heure))) < 24;

/* =====================================================
   avec le nombre de réservations correspondantes
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    s.user_id AS sportif_id,
    COUNT(r.id) AS total_reservations
FROM sportifs s
JOIN users u ON u.id = s.user_id
LEFT JOIN reservations r ON r.sportif_id = s.user_id
GROUP BY s.user_id, u.nom, u.prenom
ORDER BY total_reservations DESC;

/* =====================================================
   inclure le nom du coach
===================================================== */

SELECT
    u_s.nom AS sportif_nom,
    u_s.prenom AS sportif_prenom,
    u_c.nom AS coach_nom,
    u_c.prenom AS coach_prenom,
    COUNT(r.id) AS total_reservations
FROM reservations r
JOIN sportifs s ON s.user_id = r.sportif_id
JOIN users u_s ON u_s.id = s.user_id
JOIN seances se ON se.id = r.seance_id
JOIN coachs c ON c.user_id = se.coach_id
JOIN users u_c ON u_c.id = c.user_id
GROUP BY 
    u_s.nom, u_s.prenom,
    u_c.nom, u_c.prenom
ORDER BY 
    sportif_nom, total_reservations DESC;

