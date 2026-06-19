<?php

namespace Core;

class Router
{
    protected static array $routes = [];
    protected static array $groupStack = [];
    protected static array $middlewareAliases = [];

    public static function middleware(string $alias): self
    {
        static::$groupStack[] = ['middleware' => [$alias]];
        return new static;
    }

    public static function group(array $attributes, callable $callback): void
    {
        static::$groupStack[] = $attributes;
        $callback();
        array_pop(static::$groupStack);
    }

    public static function get(string $uri, $action): void
    {
        static::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, $action): void
    {
        static::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, $action): void
    {
        static::addRoute('PUT', $uri, $action);
    }

    public static function patch(string $uri, $action): void
    {
        static::addRoute('PATCH', $uri, $action);
    }

    public static function delete(string $uri, $action): void
    {
        static::addRoute('DELETE', $uri, $action);
    }

    public static function resource(string $name, string $controller): void
    {
        static::get("/{$name}", [$controller, 'index']);
        static::get("/{$name}/create", [$controller, 'create']);
        static::post("/{$name}", [$controller, 'store']);
        static::get("/{$name}/{id}", [$controller, 'show']);
        static::get("/{$name}/{id}/edit", [$controller, 'edit']);
        static::put("/{$name}/{id}", [$controller, 'update']);
        static::delete("/{$name}/{id}", [$controller, 'destroy']);
    }

    public static function apiResource(string $name, string $controller): void
    {
        static::get("/{$name}", [$controller, 'index']);
        static::post("/{$name}", [$controller, 'store']);
        static::get("/{$name}/{id}", [$controller, 'show']);
        static::put("/{$name}/{id}", [$controller, 'update']);
        static::delete("/{$name}/{id}", [$controller, 'destroy']);
    }

    protected static function addRoute(string $method, string $uri, $action): void
    {
        $middleware = [];
        $prefix = '';

        foreach (static::$groupStack as $group) {
            if (isset($group['middleware'])) {
                $middleware = array_merge($middleware, (array) $group['middleware']);
            }
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
        }

        $uri = $prefix . '/' . trim($uri, '/');
        $uri = rtrim($uri, '/') ?: '/';

        static::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    public static function registerMiddleware(string $alias, string $class): void
    {
        static::$middlewareAliases[$alias] = $class;
    }

    public static function getMiddlewareClass(string $alias): ?string
    {
        return static::$middlewareAliases[$alias] ?? null;
    }

    public static function dispatch(Request $request): Response
    {
        $method = $request->method();
        $uri = '/' . trim($request->path(), '/');

        foreach (static::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = static::matchUri($route['uri'], $uri);
            if ($params === false) {
                continue;
            }

            foreach ($route['middleware'] as $alias) {
                $middlewareClass = static::getMiddlewareClass($alias);
                if ($middlewareClass && class_exists($middlewareClass)) {
                    $middleware = new $middlewareClass;
                    $result = $middleware->handle($request);
                    if ($result instanceof Response) {
                        return $result;
                    }
                }
            }

            return static::executeAction($route['action'], $params);
        }

        return new Response('404 Not Found', 404);
    }

    protected static function matchUri(string $routeUri, string $requestUri): array|false
    {
        $routeSegments = explode('/', trim($routeUri, '/'));
        $requestSegments = explode('/', trim($requestUri, '/'));

        if (count($routeSegments) !== count($requestSegments)) {
            return false;
        }

        $params = [];
        for ($i = 0; $i < count($routeSegments); $i++) {
            if (preg_match('/^\{(\w+)\}$/', $routeSegments[$i], $matches)) {
                $params[$matches[1]] = $requestSegments[$i];
            } elseif ($routeSegments[$i] !== $requestSegments[$i]) {
                return false;
            }
        }

        return $params;
    }

    protected static function executeAction($action, array $params): Response
    {
        if (is_callable($action)) {
            $result = $action(...array_values($params));
        } elseif (is_array($action)) {
            [$controller, $method] = $action;
            $controllerClass = new $controller;
            $result = $controllerClass->$method(...array_values($params));
        } else {
            return new Response('Invalid route action', 500);
        }

        if ($result instanceof Response) {
            return $result;
        }

        if (is_array($result) || is_object($result)) {
            return new Response(json_encode($result), 200, ['Content-Type' => 'application/json']);
        }

        return new Response((string) $result);
    }

    public static function getRoutes(): array
    {
        return static::$routes;
    }
}
