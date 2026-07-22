<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\ScheduleModel;
use RuntimeException;
use Throwable;

/**
 * Classe parente de tous les controleurs.
 *
 * Les aides communes aux controleurs sont placees ici pour eviter de repeter
 * le meme code dans chaque controleur. Pour le squelette, elle contient
 * seulement un petit moteur de rendu de vues.
 */
abstract class BaseController
{
    /**
     * Charge un fichier de vue a l'interieur du layout principal.
     *
     * @param array<string, mixed> $data Variables rendues disponibles dans la vue.
     */
    protected function view(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutPath = dirname(__DIR__) . '/Views/layouts/main.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException('Vue introuvable : ' . $view);
        }

        $csrfToken = Security::csrfToken();
        $data = $this->withSharedViewData($data);

        extract($data, EXTR_SKIP);
        require $layoutPath;
    }

    protected function redirect(string $path): void
    {
        Response::redirect($path);
    }

    protected function e(mixed $value): string
    {
        return Security::escape($value);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function withSharedViewData(array $data): array
    {
        if (!array_key_exists('footerScheduleLines', $data) && !$this->isBackOfficeRequest()) {
            $data['footerScheduleLines'] = $this->footerScheduleLines();
        }

        return $data;
    }

    /**
     * @return list<string>
     */
    private function footerScheduleLines(): array
    {
        try {
            $schedules = (new ScheduleModel())->findAll();
        } catch (Throwable) {
            return $this->fallbackFooterScheduleLines();
        }

        $dayLabels = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche',
        ];

        $lines = [];

        foreach ($schedules as $schedule) {
            $day = (int) ($schedule['jour_semaine'] ?? 0);

            if (!isset($dayLabels[$day])) {
                continue;
            }

            $lines[] = $dayLabels[$day] . ' : ' . $this->formatScheduleSlots($schedule);
        }

        return $lines === [] ? $this->fallbackFooterScheduleLines() : $lines;
    }

    /**
     * @param array<string, mixed> $schedule
     */
    private function formatScheduleSlots(array $schedule): string
    {
        if ((int) ($schedule['ferme'] ?? 0) === 1) {
            return 'Fermé';
        }

        $slots = [];
        $morning = $this->formatScheduleSlot($schedule['ouverture_matin'] ?? null, $schedule['fermeture_matin'] ?? null);
        $afternoon = $this->formatScheduleSlot($schedule['ouverture_apres_midi'] ?? null, $schedule['fermeture_apres_midi'] ?? null);

        if ($morning !== null) {
            $slots[] = $morning;
        }

        if ($afternoon !== null) {
            $slots[] = $afternoon;
        }

        return $slots === [] ? 'Sur demande' : implode(' / ', $slots);
    }

    private function formatScheduleSlot(mixed $opening, mixed $closing): ?string
    {
        $openingTime = $this->formatScheduleTime($opening);
        $closingTime = $this->formatScheduleTime($closing);

        if ($openingTime === '' || $closingTime === '') {
            return null;
        }

        return $openingTime . ' - ' . $closingTime;
    }

    private function formatScheduleTime(mixed $time): string
    {
        $value = trim((string) $time);

        if ($value === '') {
            return '';
        }

        if (!preg_match('/^(\d{1,2}):(\d{2})/', $value, $matches)) {
            return $value;
        }

        $hours = (int) $matches[1];
        $minutes = (string) $matches[2];

        return $minutes === '00' ? $hours . 'h' : $hours . 'h' . $minutes;
    }

    /**
     * @return list<string>
     */
    private function fallbackFooterScheduleLines(): array
    {
        return [
            'Lundi : 9h - 12h30 / 14h - 18h30',
            'Mardi : 9h - 12h30 / 14h - 18h30',
            'Mercredi : 9h - 12h30 / 14h - 18h30',
            'Jeudi : 9h - 12h30 / 14h - 18h30',
            'Vendredi : 9h - 12h30 / 14h - 19h',
            'Samedi : 9h - 13h',
            'Dimanche : Fermé',
        ];
    }

    private function isBackOfficeRequest(): bool
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return str_starts_with($currentPath, '/admin') || str_starts_with($currentPath, '/employe');
    }
}
