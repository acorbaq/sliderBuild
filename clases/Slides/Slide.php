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
    // Método para renderizar la pantalla de cada slide a partir de la plantilla HTML
    public function renderScreen(array $datosWeb): string
    {
        // Cargar plantilla HTML
        $template = file_get_contents(__DIR__ . '/../../vistas/screens/screen_template.html');

        // Si existe get titulo, lo asignamos a la variable titulo que se usará en el slider
        if (isset($_GET['titulo'])) {
            $titulo = urldecode($_GET['titulo']);
            // explode por / y obtener solo la primera parte para evitar problemas con caracteres especiales en el título
            $titulo = explode('/', $titulo)[0];
        } else {
            $titulo = '';
        }
        // Reemplazar placeholders con datos del producto
        $rendered = str_replace(
            ['{{datosWeb}}', '{{titulo}}'],
            [json_encode($datosWeb), $titulo],
            $template
        );

        return $rendered;
    }
}
