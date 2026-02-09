# sliderBuild: Creación de slide para pantallas 9:16

sliderBuild es una herramienta que permite crear slides para una pantalla con una relación de aspecto de 9:16. El objetivo es hacer anuncios empleando multiples sistemas de acceso:

1. Acceso a través de un sitio web con Joomla y Virtuemart para lo que se usa un pequeño sublayout que carga dentro de products y mediante una consulta get obscura se obtiene los productos que se quieren mostrar (endpoint JSON).
2. Acceso a traves de una o multiples carpetas compartidas en la red local, donde se pueden colocar los archivos de imagen y un archivo de texto con el orden de las imágenes a mostrar.

## Requisitos

- php 8.0 o superior
- Servidor web (Apache, Nginx, etc.)
- Acceso a un sistema de archivos para almacenar las imágenes y el archivo de texto con el orden de las imágenes.
- Conexión a internet para acceder al sitio web con Joomla y Virtuemart (si se utiliza esta opción).

## Estructura del proyecto

- `index.php`: Archivo principal que se encarga de mostrar el slide.
- `config.php`: Archivo de configuración donde se pueden establecer las rutas de acceso a las imágenes y al endpoint JSON.
- `clases/`: Carpeta donde se encuentran las clases necesarias para el funcionamiento del slide.
- `vistas/`: Carpeta donde se encuentran las vistas para mostrar el slide.
- `assets/`: Carpeta donde se almacenan las imágenes y el archivo de texto con el orden de las imágenes.
- `css/`: Carpeta donde se encuentran los estilos para el slide.
- `js/`: Carpeta donde se encuentran los scripts necesarios para el funcionamiento del slide.

La carpeta adicional `virtuemart/` se utiliza para almacenar el sublayout que se carga dentro de products en Joomla y Virtuemart, permitiendo obtener los productos a mostrar mediante una consulta get obscura al endpoint JSON.
