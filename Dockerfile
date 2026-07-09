FROM richarvey/nginx-php-fpm:latest

# Set the working directory
WORKDIR /var/www/html

# Copy all project files into the container
COPY . .

# Set correct permissions so Nginx can read the files
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
