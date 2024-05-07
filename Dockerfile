# Use an official PHP base image
FROM php:7.4-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy your PHP script into the container
COPY index.php /app/index.php

# Set the working directory
WORKDIR /app

# Expose port 5000 to the outside
EXPOSE 5000

# Command to run the PHP built-in web server
CMD ["php", "-S", "0.0.0.0:5000"]
