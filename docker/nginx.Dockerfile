FROM nginx

ADD docker/configs/nginx.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www
