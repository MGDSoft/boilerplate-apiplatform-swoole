FROM openswoole/swoole:4.11.1-php8.1

# This Dockerfile is optimized for go binaries, change it as much as necessary
# for your language of choice.

#RUN apk --no-cache add ca-certificates=20190108-r0 libc6-compat=1.1.19-r10

ENV APP_PORT 9091
EXPOSE $APP_PORT

RUN apt update && apt install -y libicu-dev && rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions apcu opcache

COPY ./ /app
WORKDIR /app

RUN composer install && \
    chmod +x bin/*

ENTRYPOINT php public/index.php
