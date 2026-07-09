<?php

/**
 * Page 500 generique.
 */
?>
<section class="py-5">
    <div class="container">
        <div class="bg-white border rounded p-4">
            <p class="text-muted mb-2">Erreur 500</p>
            <h1 class="h3 mb-3">Erreur interne</h1>
            <p class="mb-0">
                Une erreur serveur est survenue. Le detail technique est masque
                en production.
            </p>

            <?php if (isset($throwable) && $throwable instanceof Throwable): ?>
                <hr>
                <pre class="small mb-0"><?= $this->e($throwable::class . ': ' . $throwable->getMessage()) ?></pre>
            <?php endif; ?>
        </div>
    </div>
</section>
