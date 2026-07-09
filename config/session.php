<?php

declare(strict_types=1);

return [
    'name' => getenv('SESSION_NAME') ?: 'vite_gourmand_session',
    'lifetime_minutes' => (int) (getenv('SESSION_LIFETIME_MINUTES') ?: 120),
    'secure' => filter_var(getenv('SESSION_SECURE') ?: false, FILTER_VALIDATE_BOOL),
    'http_only' => filter_var(getenv('SESSION_HTTP_ONLY') ?: true, FILTER_VALIDATE_BOOL),
    'same_site' => getenv('SESSION_SAME_SITE') ?: 'Lax',
];
