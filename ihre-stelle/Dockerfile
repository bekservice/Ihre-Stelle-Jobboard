FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    cron \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create cron job for Laravel scheduler
RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /var/log/cron.log 2>&1" > /etc/cron.d/laravel-scheduler
RUN chmod 0644 /etc/cron.d/laravel-scheduler
RUN crontab /etc/cron.d/laravel-scheduler

# Create log directories
RUN mkdir -p /var/log/supervisor

# Create supervisor config for cron
RUN echo '[program:cron]' > /etc/supervisor/conf.d/cron.conf && \
    echo 'command=/usr/sbin/cron -f' >> /etc/supervisor/conf.d/cron.conf && \
    echo 'autostart=true' >> /etc/supervisor/conf.d/cron.conf && \
    echo 'autorestart=true' >> /etc/supervisor/conf.d/cron.conf && \
    echo 'stdout_logfile=/var/log/cron.log' >> /etc/supervisor/conf.d/cron.conf && \
    echo 'stderr_logfile=/var/log/cron.log' >> /etc/supervisor/conf.d/cron.conf

# Create supervisor config for Laravel
RUN echo '[program:laravel]' > /etc/supervisor/conf.d/laravel.conf && \
    echo 'command=php-fpm' >> /etc/supervisor/conf.d/laravel.conf && \
    echo 'autostart=true' >> /etc/supervisor/conf.d/laravel.conf && \
    echo 'autorestart=true' >> /etc/supervisor/conf.d/laravel.conf

# Expose port 9000 and start php-fpm
EXPOSE 9000

# Start cron and php-fpm
CMD service cron start && php-fpm 