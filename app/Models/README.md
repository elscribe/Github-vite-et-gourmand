# Dossier Models

Les modeles sont responsables de l'acces aux donnees et de leur preparation.

Les futures requetes SQL utilisant PDO seront placees ici, tandis que les
controleurs resteront concentres sur le flux requete/reponse.

Les modeles peuvent etendre `App\Core\BaseModel` pour reutiliser la connexion
PDO configuree dans `config/database.php`.
