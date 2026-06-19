<?php

namespace Core;

class View
{
    protected static string $viewsPath = __DIR__ . '/../resources/views';
    protected static string $cachePath = __DIR__ . '/../storage/framework/views';

    public static function setViewsPath(string $path): void
    {
        static::$viewsPath = $path;
    }

    public static function render(string $template, array $data = []): string
    {
        $templatePath = static::$viewsPath . '/' . str_replace('.', '/', $template) . '.php';

        if (!file_exists($templatePath)) {
            throw new \Exception("View [{$template}] not found at {$templatePath}");
        }

        extract($data);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    public static function exists(string $template): bool
    {
        $templatePath = static::$viewsPath . '/' . str_replace('.', '/', $template) . '.php';
        return file_exists($templatePath);
    }

    public static function include(string $template, array $data = []): string
    {
        return static::render($template, $data);
    }

    public static function yield(string $section, string $default = ''): string
    {
        return $section ?? $default;
    }
}
