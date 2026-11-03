<?php

declare(strict_types=1);

// Processus séparé utilisé uniquement par le test de commandes simultanées.
require dirname(__DIR__) . '/bootstrap.php';

$menuId = (int) ($argv[1] ?? 0);
$customerId = (int) ($argv[2] ?? 0);

$orderId = (new App\Models\OrderModel())->create($customerId, $menuId, [
    'date_prestation' => '2030-01-01',
    'heure_livraison' => '12:00:00',
    'adresse_livraison' => '1 rue Test',
    'code_postal_livraison' => '33000',
    'ville_livraison' => 'Bordeaux',
    'distance_km' => 0.0,
    'commentaire_client' => '',
    'nombre_personnes' => 2,
]);

echo $orderId;
