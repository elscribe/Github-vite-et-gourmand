USE vite_gourmand;

CREATE OR REPLACE VIEW vue_menus_catalogue AS
SELECT
    m.id_menu,
    m.titre,
    m.description,
    m.conditions,
    m.nombre_personnes_minimum,
    m.prix_minimum,
    m.stock_disponible,
    r.libelle AS regime,
    t.libelle AS theme
FROM menus m
INNER JOIN regimes r ON r.id_regime = m.id_regime
INNER JOIN themes t ON t.id_theme = m.id_theme
WHERE m.actif = TRUE;

CREATE OR REPLACE VIEW vue_commandes_detaillees AS
SELECT
    c.id_commande,
    c.date_commande,
    c.date_prestation,
    c.heure_livraison,
    c.statut_actuel,
    c.distance_km,
    c.nombre_personnes,
    c.prix_livraison,
    c.prix_total,
    u.email AS client_email,
    CONCAT(u.prenom, ' ', u.nom) AS client,
    m.titre AS menu
FROM commandes c
INNER JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur
INNER JOIN menus m ON m.id_menu = c.id_menu;

CREATE OR REPLACE VIEW vue_avis_valides AS
SELECT
    a.id_avis,
    a.note,
    a.commentaire,
    a.created_at,
    CONCAT(u.prenom, ' ', LEFT(u.nom, 1), '.') AS auteur
FROM avis a
INNER JOIN utilisateurs u ON u.id_utilisateur = a.id_utilisateur
WHERE a.statut = 'valide';
