# Utiliser l'image de base PHP avec Apache
FROM php:8.2.12-apache

# Activer le module mod_rewrite pour Apache
RUN a2enmod rewrite

# Configurer Apache pour autoriser les réécritures d'URL
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# Copier votre application CodeIgniter dans le répertoire web d'Apache
COPY ./haproxy_manager /var/www/html/

# Exposer le port 80 pour l'application web
EXPOSE 80
