FROM richarvey/nginx-php-fpm:latest

# Copy project files
COPY . /var/www/html

# Tell the image to change its web root to your subfolder (e.g., public)
ENV WEBROOT /var/www/html/public
