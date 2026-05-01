# Usamos la versión de PHP con Apache que coincide con tu entorno
FROM php:8.2-apache

# Habilitamos mod_rewrite (útil si tu proyecto usa URLs amigables o .htaccess)
RUN a2enmod rewrite

# Instalamos las extensiones necesarias para que PHP se conecte a la base de datos
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copiamos todos los archivos de tu proyecto al contenedor
COPY . /var/www/html/

# Damos los permisos correctos a la carpeta
RUN chown -R www-data:www-data /var/www/html/