FROM php:8.1.5-fpm

ENV PHP_MEMORY_LIMIT=128M

WORKDIR /root
SHELL ["/bin/bash", "--login", "-c"]
RUN curl -sL https://raw.githubusercontent.com/nvm-sh/nvm/master/install.sh | bash -
RUN nvm install 16

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip yarnpkg libzip-dev zip \
    autoconf \
    autogen \
    wget \
    curl \
    cron \
    build-essential \
    apt-utils \
    software-properties-common \    
    libjpeg-dev \
    libmagickcore-dev \
    libmagickwand-dev \
    imagemagick \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \    
    xvfb \
    wkhtmltopdf \
    && docker-php-ext-install -j$(nproc) gd iconv pdo pdo_mysql \
    && docker-php-ext-install intl opcache \
    && pecl install imagick \
    && pecl install -n mcrypt \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install gd \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install exif \
    && docker-php-ext-enable mysqli \
    && docker-php-ext-install bcmath bz2


#RUN curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh
#RUN bash nodesource_setup.sh

#RUN apt-get install nodejs npm -y
#RUN command -v node
#RUN command -v npm

ENV TZ="Europe/Paris"
RUN echo "date.timezone = Europe/Paris" > /usr/local/etc/php/conf.d/timezone.ini
RUN date
 
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini

WORKDIR /var/www/project

#COPY app/composer.json ./
#COPY app/package.json ./

#RUN symfony composer install --no-dev
#RUN npm install
#RUN npm run build

RUN chmod -R 755 /var/www/project && chown -R www-data:www-data /var/www/project
