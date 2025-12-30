/* =====================================================
   nom, prénom
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    MONTH(r.reserved_at) AS mois,
    COUNT(*) AS total_reservations
FROM reservations r
JOIN sportifs s ON r.sportif_id = s.user_id
JOIN users u ON s.user_id = u.id
GROUP BY u.nom, u.prenom, MONTH(r.reserved_at)
ORDER BY mois, total_reservations DESC;

/* =====================================================
   nombre de réservations par mois
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    MONTH(r.reserved_at) AS mois,
    COUNT(*) AS total_reservations
FROM reservations r
JOIN sportifs s ON r.sportif_id = s.user_id
JOIN users u ON s.user_id = u.id
GROUP BY u.nom, u.prenom, MONTH(r.reserved_at)
ORDER BY mois, total_reservations DESC;

/* =====================================================
   mois et année
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    MONTH(r.reserved_at) AS mois,
    YEAR(r.reserved_at) AS annee,
    COUNT(*) AS total_reservations
FROM reservations r
JOIN sportifs s ON r.sportif_id = s.user_id
JOIN users u ON s.user_id = u.id
GROUP BY 
    u.nom, 
    u.prenom, 
    YEAR(r.reserved_at),
    MONTH(r.reserved_at)
ORDER BY 
    annee,
    mois,
    total_reservations DESC;


/* =====================================================
   ordre décroissant par nombre de réservations
===================================================== */

SELECT 
    u.nom,
    u.prenom,
    MONTH(r.reserved_at) AS mois,
    YEAR(r.reserved_at) AS annee,
    COUNT(*) AS total_reservations
FROM reservations r
JOIN sportifs s ON r.sportif_id = s.user_id
JOIN users u ON s.user_id = u.id
GROUP BY 
    u.nom, 
    u.prenom, 
    annee,
    mois
ORDER BY 
    total_reservations DESC,
    annee,
    mois;
