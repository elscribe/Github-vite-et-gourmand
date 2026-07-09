<?php

/**
 * Vue de reservation de route Sprint 0.
 */
?>
<section class="py-5">
    <div class="container">
        <div class="bg-white border rounded p-4">
            <p class="text-muted mb-2">Sprint 0 - route preparee</p>
            <h1 class="h3 mb-3"><?= $this->e($sectionTitle ?? 'Section') ?></h1>
            <p class="lead mb-3"><?= $this->e($sectionDescription ?? '') ?></p>
            <p class="mb-0 text-muted">
                Aucun comportement metier n'est implemente sur cette route.
                Elle est reservee pour le developpement fonctionnel.
            </p>
        </div>
    </div>
</section>
