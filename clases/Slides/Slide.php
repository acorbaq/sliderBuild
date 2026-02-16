<?php
// slide.php - Clase para representar cada slide del slider
// Esta clase se encarga de:
// - Asignar a la consulta realizada por el cliente un tipo de slide
// - Tratar parametros get para personalizar el slide
// - Renderizar el slide a partir de la plantilla HTML, reemplazando los placeholders con los datos del producto
declare(strict_types=1);

namespace Slides;

class Slide
{
    private array $config;
    private string $screen;
    private string $productSlide = 'slide'; // plantilla para slide de producto

    public function __construct(array $config, string $screen)
    {
        $this->config = $config;
        $this->screen = $screen;
    }
    // Método para renderizar la pantalla de cada slide a partir de la plantilla HTML
    public function renderScreen(array $datosWeb): string
    {
        // Cargar plantilla HTML
        $template = file_get_contents(__DIR__ . '/../../vistas/screens/' . $this->screen . '_template.html');

        $slides = $this->renderProductSlide($datosWeb);

        // Si existe get cl, lo asignamos a el color principal del slider (varialbe numerica de 1 a 5 para elegir entre las opciones de colores configuradas en config.php
        if (isset($_GET['cl'])) {
            $colorIndex = (int)$_GET['cl'];
            $colorOptions = $this->config['slider_colors'];
            $colorKeys = array_keys($colorOptions);
            $mainColor = $colorOptions[$colorKeys[($colorIndex - 1) % count($colorKeys)]]; // Cicla entre los colores disponibles
        } else {
            $mainColor = $this->config['slider_colors']['base']; // Color por defecto
        }

        $cssUrl = $this->config['base_url'] . 'css/styles.css';
        // Reemplazar placeholders con datos del producto
        $rendered = str_replace(
            ['{{slides}}', '{{cssUrl}}', '{{mainColor}}'],
            [$slides, $cssUrl, $mainColor],
            $template
        );

        return $rendered;
    }

    public function renderProductSlide($product): string
    {
        $template = file_get_contents(__DIR__ . '/../../vistas/slides/' . $this->productSlide . '_template.html');

        $slides = '';

        // Si existe get titulo, lo asignamos a la variable titulo que se usará en el slider
        if (isset($_GET['titulo'])) {
            $titulo = urldecode($_GET['titulo']);
            // explode por / y obtener solo la primera parte para evitar problemas con caracteres especiales en el título
            $titulo = explode('/', $titulo)[0];
        } else {
            $titulo = $product[0]['categoria'] ?? 'Ofertas'; // Título por defecto si no se proporciona
        }

        $logoUrl = $this->config['base_url'] . '../assets/images/base/Logo_web.svg';

        $primerProducto = true;
        foreach ($product as $prod) {
            // si es el primer producto añadimos la clase active para mostrarlo al cargar el slider
            if ($primerProducto) {
                $prod['active'] = 'active';
                $primerProducto = false;
            } else {
                $prod['active'] = 'inactive';
            }
            $slide = str_replace(
                ['{{logoUrl}}', '{{titulo}}', '{{nombre}}', '{{img}}', '{{precio}}', '{{moneda}}', '{{active}}'],
                [$logoUrl, $titulo, $prod['nombre'], $prod['img'], $prod['precio'], $prod['moneda'], $prod['active']],
                $template
            );
            $slides .= $slide;
        }
        return $slides;
    }
}
