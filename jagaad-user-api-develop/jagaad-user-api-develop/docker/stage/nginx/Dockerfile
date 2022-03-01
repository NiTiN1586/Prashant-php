FROM nginx

COPY ./docker/stage/nginx/jagaad-user-api.conf /etc/nginx/conf.d/default.conf

ARG USER=www-data
ARG UID=1000

RUN if [ $USER != "www-data" ]; then \
    addgroup -g ${UID} -S ${USER} \
    && adduser -D -H -u ${UID} -s /bin/bash ${USER} -G ${USER}; \
fi;

COPY --chown=${USER}:${USER} . /var/www/

CMD ["nginx", "-g", "daemon off;"]
