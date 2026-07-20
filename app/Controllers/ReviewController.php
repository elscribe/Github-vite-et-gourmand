<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Response;
use App\Core\Session;
use App\Models\ReviewModel;

/**
 * Controleur des avis client et de leur moderation.
 */
final class ReviewController extends BaseController
{
    public function create(string $orderId): void
    {
        $userId = $this->authenticatedUserId();
        $reviewModel = new ReviewModel();
        $order = $reviewModel->findReviewableOrder((int) $orderId, $userId);

        if ($order === null || $order['id_avis'] !== null) {
            Response::status(404);
            $this->view('errors/404', ['pageTitle' => 'Avis indisponible - Vite & Gourmand']);
            return;
        }

        $this->view('reviews/create', [
            'pageTitle' => 'Deposer un avis - Vite & Gourmand',
            'order' => $order,
            'old' => [],
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        $userId = $this->authenticatedUserId();
        $orderId = (int) Input::postString('id_commande');
        $note = (int) Input::postString('note');
        $commentaire = Input::postString('commentaire');
        $reviewModel = new ReviewModel();
        $order = $reviewModel->findReviewableOrder($orderId, $userId);
        $errors = [];

        if ($note < 1 || $note > 5) {
            $errors['note'] = 'La note doit etre comprise entre 1 et 5.';
        }

        if ($commentaire === '') {
            $errors['commentaire'] = 'Le commentaire est obligatoire.';
        }

        if ($order === null || $order['id_avis'] !== null) {
            $errors['order'] = 'Cette commande ne peut pas recevoir d avis.';
        }

        if ($errors !== []) {
            $this->view('reviews/create', [
                'pageTitle' => 'Deposer un avis - Vite & Gourmand',
                'order' => $order ?? ['id_commande' => $orderId, 'menu_titre' => 'Commande'],
                'old' => ['note' => $note, 'commentaire' => $commentaire],
                'errors' => $errors,
            ]);

            return;
        }

        $reviewModel->create($userId, $orderId, $note, $commentaire);
        Session::flash('success', 'Votre avis a ete envoye et attend validation.');

        $this->redirect('/commandes/' . $orderId);
    }

    public function employeeIndex(): void
    {
        $this->view('employee/reviews', [
            'pageTitle' => 'Moderation des avis - Vite & Gourmand',
            'reviews' => (new ReviewModel())->findAllForModeration(),
        ]);
    }

    public function moderate(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $status = Input::postString('statut');
        $updated = (new ReviewModel())->moderate((int) $id, $userId, $status);

        Session::flash($updated ? 'success' : 'error', $updated ? 'Avis modere.' : 'Statut d avis invalide.');
        $this->redirect('/employe/avis');
    }

    private function authenticatedUserId(): int
    {
        $userId = Session::userId();

        if ($userId === null) {
            $this->redirect('/connexion');
        }

        return $userId;
    }
}
