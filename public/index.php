<?php
// public/index.php - Pantalla de visualización del slider
// Esta pantalla deberia seguir los siguietnes pasos:
// - Carga la configuración para poder conectarse a Joomla
// - Inicializa el core sliderbuild (objetivo: ¿que debo cargar y cada cuanto lo cargo? [Inlcuye Assets y Joomla API])
// - Inicialización del scheduler (objetivo: ¿que debo mostrar y cuando lo muestro?)
// - Visualización del slider (objetivo: mostrar el slider con los datos dinamicos)
// - Delega el renderizado.
declare(strict_types=1);

// 1. Definición del entorno
define('ROOT_DIR', __DIR__ . '/../');
define('PUBLIC_DIR', __DIR__ . '//');
// 2. Carga de la configuración
$config = require ROOT_DIR . 'config.php';

// 3. Carga del autoloader
require_once ROOT_DIR . 'clases/Core/Autoloader.php';
Core\Autoloader::init(ROOT_DIR . 'clases/');

use Core\Application;

$app = new Core\Application($config);
