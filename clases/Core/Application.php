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
use Slides\Slide;

class Application
{
    protected array $config;
    protected ?AssetsClient $assetsClient = null;
    protected ?JoomlaClient $joomlaClient = null;

    // variable section
    protected array $section = array(
        'type' => 'home', // home, categoria o busqueda
        'section' => 'Últimos Productos', // nombre de la categoria, nombre del producto o termino de busqueda )
        'filters' => '', // array de filtros (precio, marca, etc.
    );

    public function __construct(array $config)
    {
        $this->config = $config;

        if ($config['url_transform']) {
            $this->parseUrl();
        }

        // Inicializar clientes de datos según configuración
        if ($config['data_source'] === 'assets' || $config['data_source'] === 'both') {
            $this->assetsClient = new AssetsClient();
        }

        if ($config['data_source'] === 'joomla' || $config['data_source'] === 'both') {
            $this->joomlaClient = new JoomlaClient($config['joomla_base_url'], $config['token']);
        }
        $this->getSlides($this->section);
    }

    /**
     * Devuelve un array de objetos Slide para la sección actual
     */
    public function getSlides(array $section): void
    {
        // Obtener productos desde clientes de datos
        $products = [];

        if ($this->joomlaClient) {
            $products = $this->joomlaClient->getProducts($section);
        }

        if ($this->assetsClient) {
            $products = array_merge($products, $this->assetsClient->getProducts($section));
        }

        // Aqui se aplican las reglas para decidir que Slide mostrar.
        $slide = new Slide();
        echo $slide->renderScreen($products);
    }

    // Método para parsear la URL y extraer la sección y filtros
    protected function parseUrl(): void
    {
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('index.php', $url);
        $urlContent = explode('/', $urlParts[1]);
        $urlType = urldecode($urlContent[1]);
        $urlSection = urldecode($urlContent[2]);
        $urlFilters = '';
        if (count($urlContent) > 3) {
            $urlFilters = urldecode(implode('/', array_slice($urlContent, 3)));
        }
        // Si urlSection tiene ~ sustituirlo por / (consultas en las que se requiere la sección padre)
        $urlSection = str_replace('~', '/', $urlSection);
        $this->section = array(
            'type' => urldecode($urlType), // home, categoria o busqueda
            'section' => urldecode($urlSection), // nombre de la categoria, nombre del producto o termino de busqueda )
            'filters' => urldecode($urlFilters), // array de filtros (precio, marca, etc.
        );
    }
}
