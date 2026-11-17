FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY --from=node:22-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:22-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

ENV COMPOSER_HOME=/composer
ENV PATH="/composer/vendor/bin:${PATH}"
RUN composer global require laravel/installer

COPY start.sh /usr/local/bin/start
RUN sed -i 's/\r$//' /usr/local/bin/start && chmod +x /usr/local/bin/start

WORKDIR /var/www

EXPOSE 8000
