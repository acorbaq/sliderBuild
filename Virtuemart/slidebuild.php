<?php

/**
 * Editar archivo '/components/com_virtuemart/sublayouts/products.php' haciendo una copia en template personalizado
 * El achivo debe quedar ubicado en '/templates/tu_template/html/com_virtuemart/sublayouts/products.php'
 * En la ultima linea antes del cierre incluit 'echo shopFunctionsF::renderVmSubLayout('sliderbuild', array('products' => $products, 'currency' => $currency, 'section' => $productTitle));
 * Con esto generaras un endpoint validado por get dinamico en todas la vistas que usen ese sublayout y podras obtener
 * los datos de los productos en formato JSON para alimentar un slider o sistema de pantallas externo.
 * -------------------------------------
 * -------------------------------------
 * Sublayout para exportación de datos JSON a sistema de pantallas (Slider)
 * Acceso: ?[hash_dia]=[token]
 **/
defined('_JEXEC') or die('Restricted access');

// 1. Configuración de seguridad temporal
$secret = date('Y-m-d');
$validGet = substr(md5($secret), 0, 16); // El nombre del parámetro GET cambia cada día
$token = 'sliderDataToken';             // El valor que debe tener dicho parámetro

$section  = $viewData['section'] ?? '';

// Lógica de Sección (Mejorada para keywords y URLs)
if (empty($section)) {
    $uri = JUri::getInstance();
    $keyword = $uri->getVar('keyword');
    if ($keyword) {
        $section = $keyword;
    } else {
        $path = explode('/', trim($uri->getPath(), '/'));
        $section = end($path);
    }
    $section = ucwords(str_replace(['-', 'results', ','], [' ', '', ' '], $section));
}

$getSection = isset($_GET['section']) ? urldecode($_GET['section']) : '';
// 2. Validación de acceso
// Verificamos si existe $_GET['hash_del_dia'] y si su valor es 'sliderDataToken'
if (isset($_GET[$validGet]) && $_GET[$validGet] === $token && $getSection === $section) {
    // 1. Desactivar el reporte de errores para que no ensucien el JSON
    error_reporting(0);
    ini_set('display_errors', 0);

    // 2. Limpiar CUALQUIER nivel de buffer abierto
    while (ob_get_level()) {
        ob_end_clean();
    }

    // 3. Forzar el inicio de un buffer nuevo para asegurar limpieza total
    ob_start();

    header('Content-Type: application/json; charset=utf-8');

    // 3. Inicialización de datos
    $products = $viewData['products'] ?? [];
    $allSliderProducts = [];

    // 4. Procesamiento de productos
    foreach ($products as $product) {
        $tipoPeso = '';
        $precioCalculado = $product->prices['salesPriceTt'];

        // Lógica de producto al peso (Custom Field ID 3)
        if (isset($product->customfieldsSorted['addtocart'])) {
            foreach ($product->customfieldsSorted['addtocart'] as $c) {
                if (trim($c->virtuemart_custom_id) == 3) {
                    $tipoPeso = '/KG';
                    $tipo = $c->customfield_value;

                    // Extraer valor y unidad (ej: "500 gr")
                    $partes = explode(' ', trim($tipo));
                    $valor = floatval(str_replace(',', '.', $partes[0]));
                    $unidad = strtolower($partes[1] ?? '');

                    $factorPeso = 1;
                    switch ($unidad) {
                        case 'g':
                        case 'gr':
                        case 'grs':
                        case 'gramos':
                            $factorPeso = ($valor / 1000);
                            break;
                        case 'kg':
                        case 'kilo':
                        case 'kilos':
                            $factorPeso = $valor;
                            break;
                    }

                    if ($factorPeso > 0) {
                        $precioCalculado = $product->prices['salesPriceTt'] / $factorPeso;
                    }
                    break;
                }
            }
        }

        $precioFinalTexto = number_format((float)$precioCalculado, 2, ',', '.') . ' €' . $tipoPeso;

        $allSliderProducts[] = [
            'name'  => $product->product_name,
            'price' => $precioFinalTexto,
            'link'  => $product->link,
            // Usamos file_url para máxima calidad en pantallas grandes
            'image' => isset($product->images[0]) ? JURI::root() . $product->images[0]->file_url : ''
        ];
    }

    // 5. Salida y Cierre
    $jsonOutput = json_encode([
        'action'   => 'updateSlider',
        'date'     => $secret,
        'section'  => trim($section),
        'products' => $allSliderProducts
    ], JSON_UNESCAPED_UNICODE);

    echo $jsonOutput;

    // 4. Detener todo inmediatamente
    $app = JFactory::getApplication();
    $app->close();
}
