FROM php:7.4-apache
FROM osrm/osrm-backend:latest
RUN apt-get update && apt-get install -y wget
RUN wget http://download.geofabrik.de/europe/belgium-latest.osm.pbf
RUN usr/local/bin/osrm-extract -p car.lua belgium-latest.osm.pbf
RUN /usr/local/bin/osrm-partition belgium-latest.osrm
RUN /usr/local/bin/osrm-customize belgium-latest.osrm
RUN /usr/local/bin/osrm-contract belgium-latest.osrm
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install -y libmcrypt-dev && pecl install mcrypt-1.0.3 && docker-php-ext-enable mcrypt
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


FROM nginx:latest
COPY static-html-directory ./code/frontend_pages

