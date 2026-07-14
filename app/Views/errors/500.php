<?php

/**
 * Page 500 generique.
 */
?>
<section class="page-section">
    <div class="container">
        <div class="page-panel">
            <p class="section-kicker">Erreur 500</p>
            <h1>Erreur interne</h1>
            <p>
                Une erreur serveur est survenue. Le detail technique est masque
                en production.
            </p>

            <?php if (isset($throwable) && $throwable instanceof Throwable): ?>
                <hr>
                <pre class="debug-message"><?= $this->e($throwable::class . ': ' . $throwable->getMessage()) ?></pre>
            <?php endif; ?>
        </div>
    </div>
</section>
