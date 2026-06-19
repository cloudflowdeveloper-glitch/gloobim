<?php

namespace Core;

class Config
{
    protected static array $items = [];

    public static function load(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            static::$items[$key] = require $file;
        }
    }

    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = static::$items;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public static function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $items = &static::$items;

        foreach ($keys as $i => $segment) {
            if ($i === count($keys) - 1) {
                $items[$segment] = $value;
            } else {
                if (!isset($items[$segment]) || !is_array($items[$segment])) {
                    $items[$segment] = [];
                }
                $items = &$items[$segment];
            }
        }
    }

    public static function has(string $key): bool
    {
        return static::get($key) !== null;
    }

    public static function all(): array
    {
        return static::$items;
    }
}
