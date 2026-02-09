<?php

declare(strict_types=1);

namespace Core;

class Autoloader
{
    protected static string $basePath;

    public static function init(string $basePath): void
    {
        self::$basePath = rtrim($basePath, '/');

        spl_autoload_register([self::class, 'autoload']);
    }

    protected static function autoload(string $class): void
    {
        // Leer los directorios dentro de BasePath para obtener los namespaces registrados
        $prefixes = array_filter(scandir(self::$basePath), function ($item) {
            return $item !== '.' && $item !== '..' && is_dir(self::$basePath . '/' . $item);
        });

        foreach ($prefixes as $prefix) {
            if (str_starts_with($class, $prefix . '\\') || $class === $prefix) {
                $relativeClass = str_replace('\\', '/', $class);
                $file = self::$basePath . '/' . $relativeClass . '.php';

                if (is_file($file)) {
                    require $file;
                }
                return;
            }
        }
    }
}
