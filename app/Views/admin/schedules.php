<?php
/**
 * @var list<array<string, mixed>> $schedules
 */
$dayLabels = [
    1 => 'Lundi',
    2 => 'Mardi',
    3 => 'Mercredi',
    4 => 'Jeudi',
    5 => 'Vendredi',
    6 => 'Samedi',
    7 => 'Dimanche',
];
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <h1>Horaires</h1>
            <p class="muted-text">Ces horaires servent de referentiel pour l'affichage public.</p>
        </div>

        <form class="table-wrapper schedule-form" action="/admin/horaires" method="post">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <table class="data-table">
                <caption class="visually-hidden">Horaires d'ouverture par jour avec creneaux matin, apres-midi et fermeture</caption>
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Matin</th>
                        <th>Apres-midi</th>
                        <th>Ferme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                        <?php $day = (int) $schedule['jour_semaine']; ?>
                        <tr>
                            <td><?= $this->e($dayLabels[$day]) ?></td>
                            <td>
                                <input name="ouverture_matin_<?= $day ?>" type="time" value="<?= $this->e(substr((string) ($schedule['ouverture_matin'] ?? ''), 0, 5)) ?>">
                                <input name="fermeture_matin_<?= $day ?>" type="time" value="<?= $this->e(substr((string) ($schedule['fermeture_matin'] ?? ''), 0, 5)) ?>">
                            </td>
                            <td>
                                <input name="ouverture_apres_midi_<?= $day ?>" type="time" value="<?= $this->e(substr((string) ($schedule['ouverture_apres_midi'] ?? ''), 0, 5)) ?>">
                                <input name="fermeture_apres_midi_<?= $day ?>" type="time" value="<?= $this->e(substr((string) ($schedule['fermeture_apres_midi'] ?? ''), 0, 5)) ?>">
                            </td>
                            <td>
                                <label class="checkbox-label">
                                    <input name="ferme_<?= $day ?>" type="checkbox" value="1" <?= ((int) $schedule['ferme'] === 1) ? 'checked' : '' ?>>
                                    Ferme
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="schedule-actions">
                <button class="primary-link" type="submit">Enregistrer les horaires</button>
            </div>
        </form>
    </div>
</section>
