FROM php:8.4-fpm AS app-base

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && sed -i 's/^user = .*/user = www-data/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^group = .*/group = www-data/' /usr/local/etc/php-fpm.d/www.conf \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

FROM app-base AS build

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN npm run build && rm -rf node_modules

FROM app-base AS production

RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=build /var/www/html /var/www/html

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]
