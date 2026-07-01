USE vite_gourmand;

INSERT INTO roles (id_role, libelle) VALUES
(1, 'utilisateur'),
(2, 'employe'),
(3, 'administrateur');

INSERT INTO utilisateurs
(id_utilisateur, id_role, email, password_hash, nom, prenom, telephone, adresse_postale, ville, pays, actif)
VALUES
(1, 1, 'client.demo@example.com', '$2y$10$demoHashUtilisateur', 'Martin', 'Claire', '0601020304', '12 rue Sainte-Catherine', 'Bordeaux', 'France', TRUE),
(2, 2, 'employe.demo@example.com', '$2y$10$demoHashEmploye', 'Durand', 'Julie', '0605060708', '4 quai des Chartrons', 'Bordeaux', 'France', TRUE),
(3, 3, 'admin.jose@example.com', '$2y$10$demoHashAdministrateur', 'Garcia', 'Jose', '0611223344', '8 place de la Bourse', 'Bordeaux', 'France', TRUE);

INSERT INTO regimes (id_regime, libelle) VALUES
(1, 'classique'),
(2, 'vegetarien'),
(3, 'vegan'),
(4, 'sans_gluten');

INSERT INTO themes (id_theme, libelle) VALUES
(1, 'classique'),
(2, 'noel'),
(3, 'paques'),
(4, 'anniversaire');

INSERT INTO menus
(id_menu, id_regime, id_theme, titre, description, conditions, nombre_personnes_minimum, prix_minimum, stock_disponible, actif)
VALUES
(1, 1, 1, 'Menu Terroir Bordelais', 'Menu complet pour repas familial ou professionnel.', 'Commander au moins 72 heures avant la prestation. Conserver au frais.', 6, 180.00, 5, TRUE),
(2, 2, 4, 'Menu Jardin de Saison', 'Menu vegetarien compose de produits de saison.', 'Commander au moins 48 heures avant la prestation.', 4, 120.00, 8, TRUE),
(3, 1, 2, 'Menu Noel Gourmand', 'Menu festif pour repas de fin d annee.', 'Commander deux semaines avant la prestation.', 10, 420.00, 3, TRUE);

INSERT INTO menu_images (id_menu, url, texte_alternatif, position) VALUES
(1, '/images/menus/terroir-bordelais.jpg', 'Plateau du menu Terroir Bordelais', 1),
(2, '/images/menus/jardin-saison.jpg', 'Assiette vegetarienne du menu Jardin de Saison', 1),
(3, '/images/menus/noel-gourmand.jpg', 'Table festive du menu Noel Gourmand', 1);

INSERT INTO plats (id_plat, titre_plat, type_plat, description) VALUES
(1, 'Veloute de potimarron', 'entree', 'Veloute doux aux graines grillees.'),
(2, 'Filet de canette sauce vin rouge', 'plat', 'Plat principal classique bordelais.'),
(3, 'Tarte fine aux pommes', 'dessert', 'Dessert maison.'),
(4, 'Salade croquante de saison', 'entree', 'Entree vegetarienne.'),
(5, 'Risotto aux legumes', 'plat', 'Plat vegetarien.'),
(6, 'Buche chocolat noisette', 'dessert', 'Dessert festif.');

INSERT INTO menu_plats (id_menu, id_plat, position) VALUES
(1, 1, 1), (1, 2, 2), (1, 3, 3),
(2, 4, 1), (2, 5, 2), (2, 3, 3),
(3, 1, 1), (3, 2, 2), (3, 6, 3);

INSERT INTO allergenes (id_allergene, libelle) VALUES
(1, 'gluten'),
(2, 'lait'),
(3, 'oeufs'),
(4, 'fruits_a_coque');

INSERT INTO plat_allergenes (id_plat, id_allergene) VALUES
(1, 2),
(3, 1),
(3, 3),
(6, 2),
(6, 4);

INSERT INTO commandes
(id_commande, id_utilisateur, id_menu, date_commande, date_prestation, heure_livraison, adresse_livraison, ville_livraison, distance_km, nombre_personnes, prix_menu, remise, prix_livraison, prix_total, statut_actuel, materiel_prete, materiel_retourne)
VALUES
(1, 1, 1, '2026-06-01 10:30:00', '2026-06-15', '12:00:00', '12 rue Sainte-Catherine', 'Bordeaux', 0.00, 6, 180.00, 0.00, 0.00, 180.00, 'terminee', FALSE, FALSE),
(2, 1, 3, '2026-06-05 14:15:00', '2026-12-24', '19:00:00', '20 avenue Victor Hugo', 'Pessac', 10.00, 15, 630.00, 63.00, 10.90, 577.90, 'acceptee', TRUE, FALSE);

INSERT INTO commande_statuts (id_commande, id_utilisateur, statut, commentaire, created_at) VALUES
(1, 1, 'en_attente', 'Commande creee par le client.', '2026-06-01 10:30:00'),
(1, 2, 'acceptee', 'Commande validee par l equipe.', '2026-06-01 11:00:00'),
(1, 2, 'en_preparation', 'Preparation lancee.', '2026-06-15 08:00:00'),
(1, 2, 'en_cours_de_livraison', 'Depart livraison.', '2026-06-15 11:15:00'),
(1, 2, 'livre', 'Commande livree.', '2026-06-15 12:00:00'),
(1, 2, 'terminee', 'Commande cloturee.', '2026-06-15 12:15:00'),
(2, 1, 'en_attente', 'Commande creee par le client.', '2026-06-05 14:15:00'),
(2, 2, 'acceptee', 'Commande acceptee apres verification.', '2026-06-05 16:00:00');

INSERT INTO avis
(id_avis, id_utilisateur, id_commande, note, commentaire, statut, created_at, moderated_at, moderated_by)
VALUES
(1, 1, 1, 5, 'Tres bonne prestation, livraison ponctuelle et plats savoureux.', 'valide', '2026-06-16 09:00:00', '2026-06-16 11:00:00', 2);

INSERT INTO horaires
(jour_semaine, ouverture_matin, fermeture_matin, ouverture_apres_midi, fermeture_apres_midi, ferme)
VALUES
(1, '09:00:00', '12:00:00', '14:00:00', '18:00:00', FALSE),
(2, '09:00:00', '12:00:00', '14:00:00', '18:00:00', FALSE),
(3, '09:00:00', '12:00:00', '14:00:00', '18:00:00', FALSE),
(4, '09:00:00', '12:00:00', '14:00:00', '18:00:00', FALSE),
(5, '09:00:00', '12:00:00', '14:00:00', '18:00:00', FALSE),
(6, '09:00:00', '12:00:00', NULL, NULL, FALSE),
(7, NULL, NULL, NULL, NULL, TRUE);

INSERT INTO contact_messages (titre, email, description, traite) VALUES
('Demande devis buffet', 'visiteur@example.com', 'Bonjour, je souhaite un devis pour 20 personnes.', FALSE);
