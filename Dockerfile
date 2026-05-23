FROM php:8.3-fpm

# システムパッケージ
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 依存関係をインストール（キャッシュ活用のため先にコピー）
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY package.json package-lock.json ./
RUN npm ci

# アプリ全体をコピー
COPY . .

# フロントエンドビルド
RUN npm run build

# パーミッション設定
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# NGINX 設定
COPY nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD ["/bin/bash", "/var/www/html/start.sh"]
