FROM alpine:latest

RUN apk update && apk upgrade
RUN apk add lighttpd lighttpd-mod_auth
RUN apk add php81 php81-cgi php81-fpm php81-gd php81-gettext php81-iconv php81-fileinfo

WORKDIR /app

# Overrideable, Docker-specific config
COPY config /app/config
COPY config/config.docker.php /app/config/config.php

# Setup the cronjob to rotate user credentials every hour
RUN crontab -l | { cat; echo "* 5 * * * bash /app/bin/raamen-creds.php"; } | crontab -

# Copy PHP scripts to container
COPY bin/raamen-creds.php /app/bin/raamen-creds.php
COPY www /app/www
COPY tools /app/tools

# Create tmp directory for robocheck answers
COPY tmp /app/tmp
RUN chown -R nobody:nobody /app/tmp
RUN chown -R nobody:nobody /app/www/robocheck

RUN mkdir /var/run/php
RUN chown nobody:nobody /var/run/php

EXPOSE 80

STOPSIGNAL SIGTERM
CMD php81 ./bin/raamen-creds.php && crond && php-fpm81 -D -c /app/config/php && lighttpd -D -f /app/config/lighttpd/lighttpd.conf
