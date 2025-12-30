/* =====================================================
    qui n’ont aucune réservation depuis 60 jours
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    c.user_id AS coach_id
FROM coachs c
JOIN users u ON u.id = c.user_id
LEFT JOIN seances s ON s.coach_id = c.user_id
LEFT JOIN reservations r ON r.seance_id = s.id
GROUP BY c.user_id, u.nom, u.prenom
HAVING 
    MAX(r.reserved_at) IS NULL
    OR MAX(r.reserved_at) < (NOW() - INTERVAL 60 DAY);

/* =====================================================
   mais dont les sportifs ont réservé des séances récemment
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    c.user_id AS coach_id
FROM coachs c
JOIN users u ON u.id = c.user_id
WHERE EXISTS (
    SELECT 1
    FROM reservations r
    WHERE r.reserved_at >= NOW() - INTERVAL 30 DAY
);
