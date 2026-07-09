FROM richarvey/index.php:latest

# Copy your PHP website files into the container
COPY . /var/www/html

# Tell Render to use port 80
EXPOSE 80
