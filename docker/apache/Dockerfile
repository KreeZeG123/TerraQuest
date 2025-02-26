FROM php:8.2-apache

# Activer mod_rewrite pour Symfony
RUN a2enmod rewrite

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Exposer le port 80
EXPOSE 80

# Mettre à jour la configuration d'Apache pour pointer sur le dossier public de Symfony
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

CMD ["apache2-foreground"]
