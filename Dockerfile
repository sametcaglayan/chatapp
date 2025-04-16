FROM php:8.2-cli

# Çalışma dizini olarak /var/www/html belirle
WORKDIR /var/www/html

# Gerekli sistem paketlerini yükle
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    gnupg \
    lsb-release

# Node.js ve npm yüklemek için NodeSource reposunu ekle
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# PHP uzantılarını yükle
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# GD uzantısını yapılandır ve yükle
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Composer'ı yükle
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Nginx yapılandırma dosyasını kopyala
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# Proje dosyalarını kopyala
COPY . .

# PHP bağımlılıklarını yükle
RUN composer install

# Dosya izinlerini www-data'ya ver
RUN chown -R www-data:www-data /var/www/html
