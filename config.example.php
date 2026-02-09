<?php

declare(strict_types=1);

return [
    // URL base de Joomla donde se encuentra el endpoint del slider
    'joomla_base_url' => 'http://localhost/Joomla/index.php',

    // Token de seguridad para validar el acceso al endpoint
    'token' => 'sliderDataToken',

    // Tiempo de caché de los datos del slider (en segundos)
    'cache_ttl' => 300, // 5 minutos

    // Opciones de depuración
    'debug' => true,

    // Opciones de logs (si quieres registrar errores o peticiones)
    'log_path' => __DIR__ . '/logs/',

    // Número máximo de productos a solicitar por sección
    'max_products' => 40,
];
