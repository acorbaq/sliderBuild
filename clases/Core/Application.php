<?php
// application.php - Clase principal del core del slider
// Esta clase debería ser responsable de:
// - Inicializar todo: configuración, clientes, caché
// - Orquestar la lógica: qué datos tomar, en qué orden, según reglas del Scheduler
// - Entregar datos listos para la vista: no renderiza, solo prepara
// - Ser el punto de entrada único: el index.php solo llama a Application para obtener todo lo necesario
declare(strict_types=1);

namespace Core;

use Data\AssetsClient;
use Data\JoomlaClient;

class Application
{
    protected array $config;
    protected ?AssetsClient $assetsClient = null;
    protected ?JoomlaClient $joomlaClient = null;

    public function __construct(array $config)
    {
        $this->config = $config;

        // Inicializar clientes de datos según configuración
        if ($config['data_source'] === 'assets' || $config['data_source'] === 'both') {
            $this->assetsClient = new AssetsClient();
        }

        if ($config['data_source'] === 'joomla' || $config['data_source'] === 'both') {
            $this->joomlaClient = new JoomlaClient($config['joomla_base_url'], $config['token']);
        }
    }

    /**
     * Devuelve un array de objetos Slide para la sección actual
     */
    public function getSlides(string $section): array
    {
        if ($slidesData !== null) {
            return $this->buildSlideObjects($slidesData);
        }

        // Obtener productos desde clientes de datos
        $products = [];

        if ($this->joomlaClient) {
            $products = $this->joomlaClient->getProducts($section);
        }

        if ($this->assetsClient) {
            $products = array_merge($products, $this->assetsClient->getProducts($section));
        }
    }
}
