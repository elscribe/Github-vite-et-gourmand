-- Mise à jour d'une base existante, à appliquer une seule fois après sauvegarde.
-- Les commandes déjà présentes n'ont pas réservé de stock dans l'ancienne version.
ALTER TABLE `commandes`
    ADD COLUMN `stock_reserve` BOOLEAN NOT NULL DEFAULT FALSE AFTER `statut_actuel`;
