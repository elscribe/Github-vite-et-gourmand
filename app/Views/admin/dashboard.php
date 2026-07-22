<?php
/**
 * @var array{orders_to_process: int, revenue_today: float, kitchen_delivery_followups: int, pending_reviews: int} $adminStats
 * @var list<array<string, mixed>> $ordersToProcess
 * @var list<array<string, mixed>> $reviewsToModerate
 * @var array<string, string> $statusLabels
 */
$employeeStats = $adminStats;
$orderManagementBasePath = '/admin/commandes';
$reviewManagementBasePath = '/admin/avis';

require dirname(__DIR__) . '/employee/dashboard.php';
