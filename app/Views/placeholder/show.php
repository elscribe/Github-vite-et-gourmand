<?php

/**
 * Vue temporaire pour les routes pas encore developpees.
 *
 * @var bool|null $isPublicInfoPage
 * @var string|null $sectionTitle
 * @var string|null $sectionDescription
 * @var string|null $contentTitle
 * @var list<string>|null $contentLines
 * @var string|null $keyPoint
 */
?>
<?php if (!empty($isPublicInfoPage)): ?>
<section class="legal-page-section">
    <div class="container legal-page-container">
        <header class="legal-page-header">
            <p>Visiteur / client</p>
            <h1><?= $this->e($sectionTitle ?? 'Information') ?></h1>
            <span><?= $this->e($sectionDescription ?? '') ?></span>
        </header>

        <article class="legal-page-card">
            <h2><?= $this->e($contentTitle ?? 'Informations') ?></h2>

            <div class="legal-page-content">
                <?php foreach (($contentLines ?? []) as $line): ?>
                    <p><?= $this->e($line) ?></p>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($keyPoint)): ?>
                <p class="legal-page-key-point"><?= $this->e($keyPoint) ?></p>
            <?php endif; ?>
        </article>
    </div>
</section>
<?php return; ?>
<?php endif; ?>

<section class="page-section">
    <div class="container">
        <div class="page-panel">
            <p class="section-kicker">
                Page en preparation
            </p>
            <h1><?= $this->e($sectionTitle ?? 'Section') ?></h1>
            <p><?= $this->e($sectionDescription ?? '') ?></p>
            <p class="muted-text">
                Cette section sera completee pendant le developpement des
                fonctionnalites de l'application.
            </p>
        </div>
    </div>
</section>
