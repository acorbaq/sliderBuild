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
}
