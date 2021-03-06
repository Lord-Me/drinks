<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\ErrorController;
use App\Utils\DependencyInjector;

class Router
{
    private $di;
    private $routeMap;
    private static $regexPatters = [
        'number' => '\d+',
        'string' => '\w+',
    ];

    public function __construct(DependencyInjector $di)
    {
        $this->di = $di;

        $json = file_get_contents(__DIR__ . '/../../config/routes.json');

        $this->routeMap = json_decode($json, true);
    }

    public function route(Request $request): ?string
    {
        $path = $request->getPath();

        foreach ($this->routeMap as $route => $info) {
            $regexRoute = $this->getRegexRoute($route, $info);

            if (preg_match("@^/${regexRoute}$@", $path)) {
                return $this->executeController($route, $path, $info, $request);
            }
        }

        $errorController = new ErrorController($this->di, $request);

        return $errorController->notFound();
    }

    private function getRegexRoute(string $route, array $info): string
    {
        if (isset($info['params'])) {
            foreach ($info['params'] as $name => $type) {
                $route = str_replace(':' . $name, self::$regexPatters[$type], $route);
            }
        }

        return $route;
    }

    private function executeController(
        string $route,
        string $path,
        array $info,
        Request $request
    ): ?string {
        $controllerName = '\App\Controller\\' . $info['controller'] . 'Controller';
        $controller = new $controllerName($this->di, $request);

        if (isset($info['login']) && $info['login']) {
            if ($request->getCookies()->has('user')) {
                $customerId = $request->getCookies()->get('user');
                $controller->setCustomerId($customerId);
            } else {
                $errorController = new CustomerController($this->di, $request);

                return $errorController->login();
            }
        }

        $params = $this->extractParams($route, $path);

        return call_user_func_array([$controller, $info['method']], $params);
    }

    private function extractParams(string $route, string $path): array
    {
        $params = [];

        $pathParts = explode('/', $path);
        $routeParts = explode('/', $route);

        foreach ($routeParts as $key => $routePart) {
            if (0 === mb_strpos($routePart, ':')) {
                $name = mb_substr($routePart, 1);
                $params[$name] = $pathParts[$key + 1];
            }
        }

        return $params;
    }
    // A partir del controlador, el mètode, i els paràmetres genera la url
    // Afegim el paràmetres $qs que genera la url amb querystring.
    public function generateURL($controller, $method, array $params = [], array $qs = []): string
    {
        //var_dump($this->routeMap);
        foreach ($this->routeMap as $path => $route) {
            if ($route['controller'] === $controller && $route['method'] === $method) {
                foreach ($params as $name => $value) {
                    $path = str_replace(":${name}", $value, $path);
                }
                // Gestió del QueryString
                if (count($qs)>0) {
                    $path .= "?";
                    $path .= http_build_query($qs);
                }
                return "/${path}";
            }
        }
        return '/';
    }
}
