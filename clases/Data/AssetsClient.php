<?php
// assetsClient.php - Cliente para obtener datos desde assets/images
// Esta clase debería ser responsable de:
// - Leer los archivos de la carpeta assets/images
// - Extraer la información relevante (nombre, descripción, precio, etc.) de cada imagen
// - Decidir si una imagen corresponde a un producto o no (por ejemplo, usando un formato de nombre específico o metadatos)
// - Devolver un array de objetos Slide con los datos extraídos
declare(strict_types=1);

namespace Data;

class AssetsClient
{
    protected string $assetsDir;

    public function __construct()
    {
        $this->assetsDir = ROOT_DIR . 'assets/images/';
        echo "AssetsClient initialized with assets directory: {$this->assetsDir}\n";
    }
}
