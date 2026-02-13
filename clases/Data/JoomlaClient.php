<?php
// JoomlaClient.php - Cliente para obtener datos desde la API de Joomla
// Esta clase debería ser responsable de:
// - Realizar peticiones HTTP al endpoint de Joomla para obtener los datos del slider
// - Manejar la autenticación usando el token de seguridad
// - Procesar la respuesta y extraer la información relevante (nombre, descripción, precio, etc.)
// - Devolver un array de objetos Slide con los datos extraídos
declare(strict_types=1);

namespace Data;

class JoomlaClient
{
    protected string $baseUrl;
    protected string $token;

    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    // Metodos para obtener productos Json desde Joomla
    public function getProducts(array $section): array
    {
        $url = $this->makeUrl($section);
        // 1. Petición cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Evita que el slider se quede colgado si la web no responde
        // User agent opcional para que Joomla no lo bloquee como bot genérico
        curl_setopt($ch, CURLOPT_USERAGENT, 'SliderSystem-Client');

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // 2. Procesamiento if ($http_code === 200 && !empty($response)) { $data = json_decode($response, true); if (json_last_error() === JSON_ERROR_NONE) { // Todo OK return $data; } else { echo "Error: La respuesta no es un JSON válido. Revisa si hay errores PHP en el emisor."; return []; } } else { echo "Error en la conexión. Código HTTP: " . $http_code; return []; }

        if ($http_code === 200 && !empty($response)) {
            $data = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                // Todo OK
            } else {
                echo "Error: La respuesta no es un JSON válido. Revisa si hay errores PHP en el emisor.";
            }
        } else {
            echo "Error en la conexión. Código HTTP: " . $http_code;
        }
        $processedData = $this->processProducts($data);
        // Si todo Ok sedevuelve array data
        return $processedData;
    }

    // Metodo para construir la url de consulta
    protected function makeUrl(array $section): string
    {
        $hash_key = substr(md5(date('Y-m-d')), 0, 16);
        switch ($section['type']) {
            case 'home':
                $params = [
                    $hash_key => $this->token,
                    'section' => urlencode($section['section']),
                ];
                return $this->baseUrl . '?' . http_build_query($params);
                break;
            case 'categoria':
                $params = [
                    $hash_key => $this->token,
                    'section' => urlencode($section['section']),
                ];
                if (!empty($section['filters'])) {
                    $this->baseUrl .= '/' . $section['filters'];
                }
                $url = $this->baseUrl . '/' . urlencode($section['section'])  . '?' . http_build_query($params);
                return $url;
                break;
            case 'busqueda':
                $params = [
                    $hash_key => $this->token,
                    'section' => urlencode($section['section']),
                    'keyword' => urlencode($section['section']),
                ];
                if (!empty($section['filters'])) {
                    $this->baseUrl .= '/' . $section['filters'];
                } else {
                    $this->baseUrl .= '/results,1-4';
                }
                $url = $this->baseUrl . '?' . http_build_query($params);
                return $url;
                break;
            default:
                header('Location:' . $this->baseUrl);
                return $this->baseUrl;
        }
    }

    /**
     * Metodo para devolver productos con: nombre, precio, imagen, link y categoria
     */
    protected function processProducts(array $data): array
    {
        $processed = [];
        foreach ($data['products'] ?? [] as $product) {
            $processed[] = [
                'nombre' => $product['name'] ?? '',
                'precio' => $product['price'] ?? '',
                'img' => $product['image'] ?? '',
                'link' => $product['link'] ?? '',
                'moneda' => $product['moneda'] ?? '',
                'categoria' => $data['section'] ?? '',
            ];
        }
        return $processed;
    }
}
