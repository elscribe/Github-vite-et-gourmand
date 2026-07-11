<?php

declare(strict_types=1);

namespace App\Core;

use App\Middlewares\MiddlewareInterface;

/**
 * Routeur tres simple pour un projet MVC en PHP natif.
 *
 * Le routeur relie un chemin d'URL, comme "/", a une methode de controleur.
 * Il peut aussi executer des middlewares simples avant le controleur.
 */
final class Router
{
    /**
     * @var array<string, list<array{path: string, pattern: string, parameters: list<string>, handler: array{0: class-string, 1: string}, middlewares: list<MiddlewareInterface>}>>
     */
    private array $routes = [];

    /**
     * @var array{0: class-string, 1: string}|null
     */
    private ?array $notFoundHandler = null;

    /**
     * Enregistre une route qui repond a une requete HTTP GET.
     *
     * @param array{0: class-string, 1: string} $handler Classe controleur et methode.
     * @param list<MiddlewareInterface> $middlewares
     */
    public function get(string $path, array $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    /**
     * Enregistre une route qui repond a une requete HTTP POST.
     *
     * @param array{0: class-string, 1: string} $handler Classe controleur et methode.
     * @param list<MiddlewareInterface> $middlewares
     */
    public function post(string $path, array $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    /**
     * Appelle la methode de controleur associee a la methode HTTP et au chemin.
     */
    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = $this->normalizePath($path);

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches) !== 1) {
                continue;
            }

            $parameters = [];

            foreach ($route['parameters'] as $parameterName) {
                $parameters[] = $matches[$parameterName] ?? null;
            }

            if (!$this->runMiddlewares($route['middlewares'])) {
                return;
            }

            $this->callHandler($route['handler'], $parameters);
            return;
        }

        if ($this->notFoundHandler === null) {
            http_response_code(404);
            echo 'Page introuvable.';
            return;
        }

        $this->callHandler($this->notFoundHandler);
    }

    /**
     * Configure le controleur appele lorsqu'aucune route ne correspond.
     *
     * @param array{0: class-string, 1: string} $handler Classe controleur et methode.
     */
    public function setNotFoundHandler(array $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    /**
     * @param list<MiddlewareInterface> $middlewares
     */
    private function runMiddlewares(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            if (!$middleware->handle()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param list<mixed> $parameters
     */
    private function callHandler(array $handler, array $parameters = []): void
    {
        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass();
        $controller->{$action}(...$parameters);
    }

    /**
     * Stocke une route en interne avec une cle normalisee.
     *
     * @param array{0: class-string, 1: string} $handler Classe controleur et methode.
     * @param list<MiddlewareInterface> $middlewares
     */
    private function addRoute(string $method, string $path, array $handler, array $middlewares = []): void
    {
        $normalizedPath = $this->normalizePath($path);

        $this->routes[strtoupper($method)][] = [
            'path' => $normalizedPath,
            'pattern' => $this->compilePattern($normalizedPath),
            'parameters' => $this->extractParameterNames($normalizedPath),
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    private function normalizePath(string $path): string
    {
        $normalizedPath = '/' . trim($path, '/');

        return $normalizedPath === '/' ? '/' : rtrim($normalizedPath, '/');
    }

    /**
     * Transforme une route comme "/menus/{id}" en expression reguliere.
     */
    private function compilePattern(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);

        return '#^' . $pattern . '$#';
    }

    /**
     * @return list<string>
     */
    private function extractParameterNames(string $path): array
    {
        preg_match_all('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', $path, $matches);

        return $matches[1] ?? [];
    }
}
