<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Models\ContactModel;

/**
 * Controleur de la page de contact publique.
 */
final class ContactController extends BaseController
{
    public function create(): void
    {
        $this->view('contact/create', [
            'pageTitle' => 'Contact - Vite & Gourmand',
            'errors' => [],
            'old' => [],
            'success' => Input::getString('success') === '1',
        ]);
    }

    public function store(): void
    {
        $name = Input::postString('nom');
        $title = Input::postString('titre');
        $email = Input::postString('email');
        $phone = Input::postString('telephone');
        $description = Input::postString('description');

        $errors = [];

        if ($name === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        if ($title === '') {
            $errors['titre'] = 'Le sujet est obligatoire.';
        }

        if ($email === '') {
            $errors['email'] = 'L’email est obligatoire.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'L’email doit être valide.';
        }

        if ($description === '') {
            $errors['description'] = 'Le message est obligatoire.';
        }

        if ($errors !== []) {
            $this->view('contact/create', [
                'pageTitle' => 'Contact - Vite & Gourmand',
                'errors' => $errors,
                'old' => [
                    'nom' => $name,
                    'titre' => $title,
                    'email' => $email,
                    'telephone' => $phone,
                    'description' => $description,
                ],
                'success' => false,
            ]);

            return;
        }

        $message = trim(implode("\n", array_filter([
            'Nom : ' . $name,
            $phone !== '' ? 'Téléphone : ' . $phone : null,
            '',
            'Message :',
            $description,
        ], static fn (?string $line): bool => $line !== null)));

        $contactModel = new ContactModel();
        $contactModel->createMessage($title, $email, $message);

        $this->redirect('/contact?success=1');
    }
}
