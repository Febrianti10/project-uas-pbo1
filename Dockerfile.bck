FROM php:8.2-apache

# Install dependencies & PHP Extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Copy application
COPY . /var/www/html/

# Set document root ke folder public (karena index.php kamu ada di sana)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 10000 (Render default)
EXPOSE 10000
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's/:80/:10000/g' /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]