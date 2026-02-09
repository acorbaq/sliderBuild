# sliderBuild: Creación de slide para pantallas 9:16

sliderBuild es una herramienta que permite crear slides para una pantalla con una relación de aspecto de 9:16. El objetivo es hacer anuncios empleando multiples sistemas de acceso:

1. Acceso a través de un sitio web con Joomla y Virtuemart para lo que se usa un pequeño sublayout que carga dentro de products y mediante una consulta get obscura se obtiene los productos que se quieren mostrar (endpoint JSON).
2. Acceso a traves de una o multiples carpetas compartidas en la red local, donde se pueden colocar los archivos de imagen y un archivo de texto con el orden de las imágenes a mostrar.

## Requisitos

- php 8.0 o superior
- Servidor web (Apache, Nginx, etc.)
- Acceso a un sistema de archivos para almacenar las imágenes y el archivo de texto con el orden de las imágenes.
- Conexión a internet para acceder al sitio web con Joomla y Virtuemart (si se utiliza esta opción).
