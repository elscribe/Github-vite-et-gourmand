USE vite_gourmand;

CREATE INDEX idx_utilisateurs_role ON utilisateurs(id_role);
CREATE INDEX idx_menus_regime ON menus(id_regime);
CREATE INDEX idx_menus_theme ON menus(id_theme);
CREATE INDEX idx_menus_prix ON menus(prix_minimum);
CREATE INDEX idx_menus_minimum ON menus(nombre_personnes_minimum);
CREATE INDEX idx_commandes_utilisateur ON commandes(id_utilisateur);
CREATE INDEX idx_commandes_menu ON commandes(id_menu);
CREATE INDEX idx_commandes_statut ON commandes(statut_actuel);
CREATE INDEX idx_commandes_date_prestation ON commandes(date_prestation);
CREATE INDEX idx_commandes_distance ON commandes(distance_km);
CREATE INDEX idx_commande_statuts_commande_date ON commande_statuts(id_commande, created_at);
CREATE INDEX idx_avis_statut ON avis(statut);
CREATE INDEX idx_contact_messages_traite ON contact_messages(traite);
CREATE INDEX idx_password_resets_utilisateur ON password_resets(id_utilisateur);
